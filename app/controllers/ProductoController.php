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


 /* public static function obtenerUsuario($usuario)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave, roll, fechaBaja FROM usuarios WHERE usuario = :usuario");
      $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
      $consulta->execute();

      return $consulta->fetchObject('Usuario');
  }

  public static function ObtenerUsuarioPorId($id)
  {
      $objAccesoDatos = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM empleados WHERE id = :id");
      $consulta->bindValue(':id', $id, PDO::PARAM_STR);
      $consulta->execute();
      return $consulta->fetchObject("usuario");
  }

  public function modificarUsuario($id)
  {
      $objAccesoDato = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave , roll = :roll WHERE id = $id");
      $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
      $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
      $consulta->bindValue(':clave', $claveHash);
      $consulta->bindValue(':roll', $this->roll, PDO::PARAM_STR);
      $consulta->execute();
  }


  public static function borrarUsuario($id)
  {
      $objAccesoDato = AccesoDatos::obtenerInstancia();
      $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
      $fecha = new DateTime(date("d-m-Y"));
      $consulta->bindValue(':id', $id, PDO::PARAM_INT);
      $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
      $consulta->execute();
  }*/
}