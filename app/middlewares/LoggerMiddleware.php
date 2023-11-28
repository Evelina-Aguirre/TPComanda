<?php

use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class LoggerMiddleware
{
    public function __invoke(Request $request, Psr\Http\Server\RequestHandlerInterface $handler): Response
    {
        // Fecha antes
        $before = date('Y-m-d H:i:s');

        // Continua al controller
        $response = $handler->handle($request);
        $existingContent = json_decode($response->getBody());

        // Después
        $response = new Response();
        $existingContent->fechaAntes = $before;
        $existingContent->fechaDespues = date('Y-m-d H:i:s');

        $payload = json_encode($existingContent);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarRol(Request $request, Psr\Http\Server\RequestHandlerInterface $handler): Response
    {
        $parametros = $request->getParsedBody();


        $sector = $parametros['roll'];

        if ($sector === 'socio') {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No sos un socio.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    
}
