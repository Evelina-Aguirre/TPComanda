<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class RegistroAccionesMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if (!empty($authorizationHeader)) {
            $token = $this->obtenerToken($authorizationHeader);

            if ($token) {
                $datosUsuario = $this->obtenerDatosUsuario($token);

                if ($datosUsuario) {

                    $rutaCompleta = $request->getUri()->getPath();

                    $posicionApp = strpos($rutaCompleta, '/app/');
                    $rutaDespuesDeApp = ($posicionApp !== false) ? substr($rutaCompleta, $posicionApp + 5) : $rutaCompleta;

                    $this->registrarAccion($datosUsuario->data->id, $datosUsuario->data->nombre, $request->getMethod(), $rutaDespuesDeApp);
                    //$this->registrarAccion($datosUsuario->data->id, $datosUsuario->data->nombre, $request->getMethod(), $request->getUri()->getPath());
                }
            }
        }


        $response = $handler->handle($request);

        return $response;
    }

    private function obtenerToken($authorizationHeader)
    {
        list($tokenType, $token) = sscanf($authorizationHeader, '%s %s');
        return $token;
    }

    private function obtenerDatosUsuario($token)
    {
        try {
            $claveSecreta = 'T3sT$JWT';
            $datosDecodificados = JWT::decode($token, $claveSecreta, ['HS256']);
            return $datosDecodificados;
        } catch (Exception $excepcion) {
            return null;
        }
    }

    private function registrarAccion($usuarioId, $nombre, $metodo, $ruta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO registroacciones (usuarioid,nombre, metodo, ruta, fecha) VALUES (?,?, ?, ?, NOW())");
        $consulta->bindValue(1, $usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(2, $nombre, PDO::PARAM_STR);
        $consulta->bindValue(3, $metodo, PDO::PARAM_STR);
        $consulta->bindValue(4, $ruta, PDO::PARAM_STR);
        $consulta->execute();
    }
}
