<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class ClienteMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        
        list($tokenType, $token) = sscanf($authorizationHeader, '%s %s');


        if (empty($token)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['Error' => 'Token invalido.']));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response = new Response();

        try {
          
            $datos = AutentificadorJWT::ObtenerData($token);

            if ($datos->roll == "Cliente") {
                printf("Realiza esta acción un Cliente");
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(['Error' => 'Solo los clientes pueden realizar esta acción.']));
            }
        } catch (Exception $excepcion) {
            $response->getBody()->write(json_encode(['Error' => $excepcion->getMessage()]));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
  

    private function getBearerToken(Request $request)
    {
        $header = $request->getHeaderLine('Authorization');
        $matches = [];
        preg_match('/Bearer\s+(.*)$/i', $header, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }
}