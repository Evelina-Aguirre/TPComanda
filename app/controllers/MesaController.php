<?php
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Usuario // implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $codigo = $parametros['codigo'];
    $estado = $parametros['estado'];
    $puntaje = $parametros['puntaje'];

    $mesa = new Mesa();
    $mesa->codigo = $codigo;
    $mesa->estado = $estado;
    $mesa->puntaje = $puntaje;
    $mesa->crearMesa();

    $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }



  public function consultarEstadoPedido($request, $response, $args)
  {
    $queryParams = $request->getQueryParams();

    $codigoMesa = $queryParams['codigoMesa'] ?? null;
    $codigoPedido = $queryParams['codigoPedido'] ?? null;

    $idMesa = Mesa::obtenerIdMesaPorCodigo($codigoMesa);

    $estadoPedido = Mesa::conocerEstadoPedido($codigoPedido, $idMesa);

    $payload = json_encode(array("estadoPedido" => $estadoPedido));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public function cobrar($request, $response, $args)
  {
    $idPedido = $args['idPedido'] ?? null;

    $idMesa = Pedido::buscarIdMesadeunPedido($idPedido);

    if ($idMesa !== false) {
      Mesa::ActualizarEstado($idMesa, 'cliente pagando');

      $successPayload = json_encode(array("mensaje" => "Estado de la mesa actualizado a 'cliente pagando'"));
      $response->getBody()->write($successPayload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    $errorPayload = json_encode(array("mensaje" => "No se pudo encontrar la mesa asociada al número de pedido proporcionado"));
    $response->getBody()->write($errorPayload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
  }


  public function cerrarMesa($request, $response, $args)
  {
    $idMesa = $args['idMesa'] ?? null;

    if ($idMesa !== false) {
      Mesa::ActualizarEstado($idMesa, 'cerrada');

      $successPayload = json_encode(array("mensaje" => "Mesa cerrada correctamente.'"));
      $response->getBody()->write($successPayload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    $errorPayload = json_encode(array("mensaje" => "No se encontró la mesa."));
    $response->getBody()->write($errorPayload);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
  }

  public function mesaMasUsada($request, $response, $args)
  {
    $mesaMasUsada = Mesa::mesaMasUsada();

    $payload = json_encode(array("mesaMasUsada" => $mesaMasUsada));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
