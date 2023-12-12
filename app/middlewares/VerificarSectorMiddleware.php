<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class VerificarSectorMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        list($tokenType, $token) = sscanf($authorizationHeader, '%s %s');

        if (empty($token)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['Error' => 'El usuario logueado no tiene las credenciales para realizar esta acción. 
            Intenta loguearte nuevamente.']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response = new Response();

        try {
            $datos = AutentificadorJWT::ObtenerData($token);

            $permisosPorSector = [
                'cocina' => 'Cocinero',
                'tragos' => 'Bartender',
                'cervezas' => 'Cervecero',
                'postres' => 'Pastelero',
            ];

            //$sectorEnRuta = $request->getAttribute('routeInfo')[2]['sector'];
            //('routeInfo')[2]['sector']
            $rutaCompleta = $request->getUri()->getPath();
            $posicionApp = strpos($rutaCompleta, '/app/');
            $rutaDespuesDeApp = ($posicionApp !== false) ? substr($rutaCompleta, $posicionApp + 5) : $rutaCompleta;

            $sectorEnRuta = explode('/', $rutaDespuesDeApp)[2];

            if (isset($permisosPorSector[$sectorEnRuta]) && $datos->roll == $permisosPorSector[$sectorEnRuta]) {
                printf(" ->Sector $sectorEnRuta");
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(['Error' => 'Acción reservada solamente para empleados del mismo sector.']));
            }
        } catch (Exception $excepcion) {
            $response->getBody()->write(json_encode(['Error' => 'Credenciales inválidas. Intenta loguearte nuevamente.']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
