<?php

require_once "./models/Login.php"; 
require_once './utils/AutentificadorJWT.php';
class LoginController extends Login
{
    public function Ingresar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];

        $id = Login::verificarCredenciales($nombre, $clave);

        if ($id !== false) {

            $token = Login::obtenerToken($id);

            if ($token) {
                $rol = Login::obtenerRoll($token);

                $payload = json_encode(array("mensaje" => "Bienvenido, te logueaste como $rol"));

                $response->getBody()->write($payload);
            } else {
                $payload = json_encode(array("error" => "No se pudo obtener el token."));
                $response->getBody()->write($payload);
            }
        } else {
            $payload = json_encode(array("error" => "Credenciales no válidas."));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function Deslogear($request, $response, $args)
    {
        $response->getBody()->write("Se cerró la sesión");
        return $response->withHeader('Content-Type', 'application/json');
    }
}
