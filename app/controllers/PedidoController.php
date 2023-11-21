<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $idListaProductos = $parametros['idListaProductos'];
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
        $pedido->idListaProductos = $idListaProductos;
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

 
}
