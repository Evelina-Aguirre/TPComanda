<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarUsuarioMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
     
        $id = $request->getAttribute('id');
        $roll = $request->getAttribute('roll');
        $nombre = $request->getAttribute('nombre');


        $response = $handler->handle($request);

        return $response->withHeader('Content-Type', 'application/json');
    }
}