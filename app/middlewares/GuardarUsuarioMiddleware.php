
<?php
require_once "./utils/AutentificadorJWT.php";
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Psr7Middlewares\Middleware\Payload;

class GuardarUsuarioMiddleware 
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    { 
        try {
            $response = $handler->handle($request);
            
            $nombre = $request->getAttribute('usuario');
            
            if ($nombre === null) {
                $parsedBody = $request->getParsedBody();
                $nombre = $parsedBody['nombre'] ?? null;
                
            }
            $this->guardarInformacionUsuario($nombre);
            
            
            $payload = json_encode(array('mensaje' => 'Usuario creado correctamente.'));
            

        $response = new Response();
        $response->getBody()->write($payload);

        return $response
          ->withHeader('Content-Type', 'application/json');

           
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(['mensaje' => 'ERROR: ' . $e->getMessage()]);
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    

    private function guardarInformacionUsuario($nombre)
    {
        
        $usuario = Usuario::obtenerUsuario($nombre);
    
    
        $datos = [
            "id" => $usuario->id,
            "nombre" => $usuario->nombre,
            "roll" => $usuario->roll,
        ];
    
        $tokenJWT = AutentificadorJWT::CrearToken($datos);
    
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consultaExistente = $objAccesoDatos->prepararConsulta("SELECT id FROM jwtUsuarios WHERE id = ?");
        $consultaExistente->bindValue(1, $usuario->id, PDO::PARAM_INT); 
        $consultaExistente->execute();
        
        $registroExistente = $consultaExistente->fetch(PDO::FETCH_ASSOC);
        
        if (!$registroExistente) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO jwtUsuarios (id, nombre, roll, token) VALUES (?, ?, ?, ?)");
        $consulta->bindValue(1, $usuario->id,PDO::PARAM_INT);
        $consulta->bindValue(2,$usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(3, $usuario->roll, PDO::PARAM_STR);
        $consulta->bindValue(4, $tokenJWT, PDO::PARAM_STR);
        $consulta->execute();}
        else{
            throw  new Exception("Este usuario ya posee una cuenta.");
        }
    }
    
    
}