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
        $fotoMesa = $request->getUploadedFiles()['fotoMesa'];

        $rutaDestino = './imagenes/' . $fotoMesa->getClientFilename();

        if ($fotoMesa->getError() === UPLOAD_ERR_OK) {

            if (!file_exists(dirname($rutaDestino))) {
                mkdir(dirname($rutaDestino), 0777, true);
            }

            $fotoMesa->moveTo($rutaDestino);
            $this->setFotoMesa($rutaDestino);
        } else {

            $this->setFotoMesa(null);
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

        if (Pedido::actualizarEstadoYHora($idPedido)) {
            $payload = json_encode(array("mensaje" => "Estado y hora del pedido actualizados correctamente."));
        } else {
            $payload = json_encode(array("error" => "Pedido no encontrado o no es necesario actualizar."));
        }
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $tiempoEstimado = $parametros['tiempoEstimado'];

        $pedido = new Pedido();

        try {
            $pedido->modificarPedido($idPedido, $idProducto, $tiempoEstimado);

            $payload = json_encode(array("mensaje" => "Pedido modificado con éxito"));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $payload = json_encode(array("error" => $e->getMessage()));
            $response->getBody()->write($payload);

            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}
