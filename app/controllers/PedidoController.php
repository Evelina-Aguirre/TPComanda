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
        $fotoMesa = $parametros['fotoMesa'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $horaCreacion =  0;//new DateTime(date("h:i:sa"));
        $horaFinalizacion = null;

        $pedido = new Pedido();
        $pedido->idListaProductos = $idListaProductos;
        $pedido->idMesa = $idMesa;
        $pedido->estado = $estado;
        $pedido->codigoPedido = $codigoPedido;
        $pedido->fotoMesa = $fotoMesa;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->horaCreacion = $horaCreacion;
        $pedido->horaFinalizacion = $horaFinalizacion;
        $pedido->AltaPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con éxito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

