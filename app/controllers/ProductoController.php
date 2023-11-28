<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Producto.php';
require_once './utils/csv.php';

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

  public function ImportarCsv($request, $response, $args)
  {
    try {

      $mensajeCarga = json_encode(array("Mensaje" => "Productos cargados."));
      $lista = Producto::obtenerTodos();

      $mensajeLista = json_encode(array("listaProductos" => $lista));
      $payload = $mensajeCarga . ' ' . $mensajeLista;

    } catch (Throwable $mensaje) {
      $payload = json_encode(array("Error" => $mensaje->getMessage()));
    } finally {
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'text/csv');
    }
  }

  public function ExportarCsv($request, $response, $args)
  {
    try {
      $listaProductos = Producto::obtenerTodos();
      $archivo = Csv::ExportarCSV("productos.csv", $listaProductos);
      if (file_exists($archivo) && filesize($archivo) > 0) {
        $payload = json_encode(array("Archivo creado:" => $archivo));
      } else {
        $payload = json_encode(array("Error" => "Datos invalidos."));
      }
      $response->getBody()->write($payload);
    } catch (Exception $e) {
      echo $e;
    } finally {
      return $response->withHeader('Content-Type', 'text/csv');
    }
  }
}
