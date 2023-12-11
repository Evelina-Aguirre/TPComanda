<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class EmpleadoMiddleware
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

            $rolesPermitidos = ["Cervecero", "Pastelero", "Bartender", "Cocinero"];

            if (in_array($datos->roll, $rolesPermitidos)) {
                printf("Quien realiza esta acción es un $datos->roll -");
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(['Error' => 'Acción reservada solamente para empleados y admin.']));
            }
        } catch (Exception $excepcion) {
            $response->getBody()->write(json_encode(['Error' => 'Credenciales inválidas. Intenta loguearte nuevamente.']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}