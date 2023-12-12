<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $listaProductos = $parametros['listaProductos'];
        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];
        $codigoPedido = $parametros['codigoPedido'];

        $fotoMesa = $request->getUploadedFiles()['fotoMesa'] ?? null;

        $rutaDestino = null;

        if ($fotoMesa && $fotoMesa->getError() === UPLOAD_ERR_OK) {
            $rutaDestino = './imagenes/' . $fotoMesa->getClientFilename();

            if (!file_exists(dirname($rutaDestino))) {
                mkdir(dirname($rutaDestino), 0777, true);
            }

            $fotoMesa->moveTo($rutaDestino);
        }

        $tiempoEstimado = $parametros['tiempoEstimado'];
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $horaCreacion = new DateTime(date("h:i:sa"));
        $horaFinalizacion = '00:00:00';

        $pedido = new Pedido();
        $pedido->listaProductos = $listaProductos;
        $pedido->idMesa = $idMesa;
        $pedido->estado = $estado;
        $pedido->codigoPedido = $codigoPedido;
        $pedido->fotoMesa = $rutaDestino;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->horaCreacion = $horaCreacion;
        $pedido->horaFinalizacion = $horaFinalizacion;
        $pedido->AltaPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function relacionarFoto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];

        $fotoMesa = $request->getUploadedFiles()['fotoMesa'] ?? null;

        if (!$fotoMesa || $fotoMesa->getError() !== UPLOAD_ERR_OK) {
            $errorPayload = json_encode(array("error" => "Error al cargar la foto de la mesa."));
            $response->getBody()->write($errorPayload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $rutaDestino = './imagenes/' . $fotoMesa->getClientFilename();

        if (!file_exists(dirname($rutaDestino))) {
            mkdir(dirname($rutaDestino), 0777, true);
        }

        $fotoMesa->moveTo($rutaDestino);

        try {
            Pedido::relacionarFotoMesa($id, $rutaDestino);

            $successPayload = json_encode(array("mensaje" => "Foto de la mesa relacionada al pedido correctamente."));
            $response->getBody()->write($successPayload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $errorPayload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($errorPayload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }


    public function TraerTodos($request, $response, $args)
    {
        $lista = pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function actualizarEstadoPedidoMesa($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $horaFinalizacion = $args['horaFinalizacion'];

        if (Pedido::actualizarHoraFinalización($idPedido, $horaFinalizacion)) {
            $payload = json_encode(array("mensaje" => "Estado y hora del pedido actualizados correctamente."));
        } else {
            $payload = json_encode(array("error" => "Pedido no encontrado o no es necesario actualizar."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'] ?? null;
        $tiempoEstimado = $parametros['tiempoEstimado'] ?? null;
        $empleadoACargo = $parametros['empleadoACargo'] ?? null;
        $estado = $parametros['estado'] ?? null;

        $pedido = new Pedido();

        try {
            $pedido->modificarPedido($id, $tiempoEstimado, $empleadoACargo, $estado);

            $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    public function listaPendientes($request, $response, $args)
    {
        $sector = $args['sector'];
        $pedidos = Pedido::listarPedidosPendientesPorRol($sector);
        $response->getBody()->write(json_encode($pedidos));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function listaProductosPedidos($request, $response, $args)
    {
        $listaProductos = $this->listarProductosPedidos();
        $payload = json_encode(array("listaProductos" => $listaProductos));
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $queryParams = $request->getQueryParams();
        $idPedido = $queryParams['id'] ?? null;

        if ($idPedido) {
            Pedido::borrarPedido($idPedido);

            $payload = json_encode(array("mensaje" => "Pedido cancelado con éxito"));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(array("error" => "ID de pedido no proporcionado"));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }


    public function servirPedido($request, $response, $args)
    {

        $pedidosListos = Pedido::verificarPedidosListos();

        if (!empty($pedidosListos)) {
            foreach ($pedidosListos as $idPedido) {
                $idMesa = Pedido::buscarIdMesadeunPedido($idPedido);
                Mesa::ActualizarEstado($idMesa, 'con cliente comiendo');
                Pedido::marcarPedidoServido($idPedido);
            }

            $successPayload = json_encode(array("mensaje" => "Pedidos servidos."));
            $response->getBody()->write($successPayload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $infoPayload = json_encode(array("mensaje" => "No hay pedidos listos para servir"));
        $response->getBody()->write($infoPayload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function pedidosConDemora($request, $response, $args)
    {
        try {
            $pedidosConDemora = Pedido::obtenerPedidosConDemora();

            $response = $response->withHeader('Content-Type', 'application/json');
            if (!empty($pedidosConDemora)) {
                $response->getBody()->write(json_encode($pedidosConDemora));
            } else {
                $response->getBody()->write(json_encode(['mensaje' => 'No hubo pedidos con demora.']));
            }

            return $response;
        } catch (Exception $e) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

            return $response;
        }
    }

    public function pedidosSinDemora($request, $response, $args)
    {
        try {
            $pedidosSinDemora = Pedido::obtenerPedidosSinDemora();

            $response = $response->withHeader('Content-Type', 'application/json');
            if (!empty($pedidosSinDemora)) {
                $response->getBody()->write(json_encode($pedidosSinDemora));
            } else {
                $response->getBody()->write(json_encode(['mensaje' => 'No se encontraron pedidos sin demora.']));
            }

            return $response;
        } catch (Exception $e) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

            return $response;
        }
    }
}
