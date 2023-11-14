<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Usuario// implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $listaPedidos = $parametros['listaPedidos'];
    $estado = $parametros['estado'];
    $puntaje = $parametros['puntaje'];

    $mesa = new Mesa();
    $mesa->listaPedidos = $listaPedidos;
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
}
