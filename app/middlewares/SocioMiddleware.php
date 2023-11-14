<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class SocioMiddleware
{
    public function __invoke(Request $request,RequestHandler $handler) : Response
    {
        $roll = $request->getParsedBody(("roll"));
        $response = new Response();
  
            if($roll->rol == "Socio")
            {
                $response= $handler->handle($request);
            }
            else
            {
                $response->getBody()->write(json_encode(array('Error' => "Debe ser socio para realizar esta acción.")));
            }
       

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>