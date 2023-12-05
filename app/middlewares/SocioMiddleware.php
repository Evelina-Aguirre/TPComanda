<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
class SocioMiddleware
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

            if ($datos->roll == "socio") {
                printf("Quien realiza esta acción es un socio ");
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(['Error' => 'Acción reservada solamente para los socios.']));
            }
        } catch (Exception $excepcion) {

            $response->getBody()->write(json_encode(['Error' => 'Credenciales inválidas. Intenta loguearte nuevamente.']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
  

}
