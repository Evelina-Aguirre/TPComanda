<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    $clave = $parametros['clave'];
    $roll = $parametros['roll'];
    $fechaAlta = isset($parametros['fechaAlta']) ? $parametros['fechaAlta'] : null;


    $usr = new Usuario();
    $usr->nombre = $nombre;
    $usr->clave = $clave;
    $usr->roll = $roll;
    $usr->fechaAlta = $fechaAlta;
    $usr->crearUsuario();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $usr = $args['nombre'];
    $usuario = Usuario::obtenerUsuario($usr);

    if ($usuario) {
      $payload = json_encode($usuario);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } else {
      $mensaje = json_encode(array("mensaje" => "Usuario no encontrado"));
      $response->getBody()->write($mensaje);
      return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['id'];
    $nombre = $parametros['nombre'];
    $clave = $parametros['clave'];
    $roll = $parametros['roll'];
    $fechaAlta = $parametros['fechaAlta'];
    $fechaBaja = $parametros['fechaBaja'];
    $estado = $parametros['estado'];

    $usr = new Usuario();
    $usr->nombre = $nombre;
    $usr->clave = $clave;
    $usr->roll = $roll;
    $usr->fechaAlta = $fechaAlta;
    $usr->fechaBaja = $fechaBaja;
    $usr->estado = $estado;
    $usr->ModificarUsuario($id);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $queryParams = $request->getQueryParams();
    $usuarioId = $queryParams['id'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
