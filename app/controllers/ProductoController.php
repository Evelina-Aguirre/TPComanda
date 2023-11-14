<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Producto.php';

class ProductoController extends Producto 
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    $sectorAsignado = $parametros['sectorAsignado'];
    $precio = $parametros['precio'];

    $prod = new Producto();
    $prod->nombre = $nombre;
    $prod->sectorAsignado = $sectorAsignado;
    $prod->precio = $precio;
    $prod->crearProducto();

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::obtenerTodos();
    $payload = json_encode(array("listaProductos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $nombre = $args['nombre'];
    $producto = Producto::ObtenerProductoPorNombre($nombre);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


}