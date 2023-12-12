<?php
require_once "./utils/AutentificadorJWT.php";
require_once "./models/Login.php";
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Psr7Middlewares\Middleware\Payload;

class LogueoMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        
        $response = $handler->handle($request);

        $params = $request->getParsedBody();
        $usuario = $params['nombre'] ?? null;
        $clave = $params['clave'] ?? null;

        $idUsuario=Login::verificarCredenciales($usuario, $clave);
        $token=Login::obtenerToken($idUsuario);

        $nombre = AutentificadorJWT::ObtenerData($token)->nombre;
        $sector = AutentificadorJWT::ObtenerData($token)->roll;


        $this->registrarLogueo($idUsuario,$nombre,$sector);


        return $response->withHeader('Content-Type', 'application/json');
    }

    private function registrarLogueo($idUsuario,$nombre,$sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("
            INSERT INTO usuarioslogueo (idUsuario,nombre, sector, fechaHoraLogueo)
            VALUES (:idUsuario,:nombre, :sector, CURRENT_TIMESTAMP())
        ");
        $consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);

        $consulta->execute();
    }
}