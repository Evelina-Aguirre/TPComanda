<?php
use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class AuthMiddleware
{

    private static $miClaveSecreta = 'T3sT$JWT'; //Clave Secreta
    private static $algoritmoDeCodificacion = ['HS256']; // Algoritmo de Codificacion
    private static $aud = null;
   
    
    public function __invoke(Request $request, Psr\Http\Server\RequestHandlerInterface $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function verificarToken(Request $request, Psr\Http\Server\RequestHandlerInterface $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerPayLoad($token)
    {
        return JWT::decode(
            $token,
            self::$miClaveSecreta,
            self::$algoritmoDeCodificacion
        );
    }
    
    public static function ObtenerDatoaAPartirDeToken($token)
    {
        
        return JWT::decode(
            $token,
            self::$miClaveSecreta,
            self::$algoritmoDeCodificacion
        )->data;
    }
}
