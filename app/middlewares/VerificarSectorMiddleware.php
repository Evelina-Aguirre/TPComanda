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
            $response->getBody()->write(json_encode(['Error' => 'El usuario logueado no tiene las credenciales para realizar esta acción. Intenta loguearte nuevamente.']));
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

            $rutaCompleta = $request->getUri()->getPath();
            $posicionApp = strpos($rutaCompleta, '/app/');
            $rutaDespuesDeApp = ($posicionApp !== false) ? substr($rutaCompleta, $posicionApp + 5) : $rutaCompleta;
            $indicesRuta = explode('/', $rutaDespuesDeApp);
            $sectorEnRuta = isset($indicesRuta[2]) ? $indicesRuta[2] : '';
            
            $body = json_decode($request->getBody(), true);
            $sectorEnCuerpo = $body['sector'] ?? '';
            
            if (!empty($sectorEnCuerpo)) {
                $sectorEnRuta = $sectorEnCuerpo;
            }

            if (isset($permisosPorSector[$sectorEnRuta]) && $datos->roll == $permisosPorSector[$sectorEnRuta]) {
                printf(" ->Sector $sectorEnRuta");
                $response = $handler->handle($request->withAttribute('sector', $sectorEnRuta));
            } else {
                $response->getBody()->write(json_encode(['Error' => 'Acción reservada solamente para empleados del mismo sector.']));
            }
        } catch (Exception $excepcion) {
            $response->getBody()->write(json_encode(['Error' => 'Credenciales inválidas. Intenta loguearte nuevamente.']));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

   /* private function obtenerSectorDesdeBaseDeDatos($idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT sector FROM listaproductosporpedido WHERE idProducto = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return ($resultado !== false) ? $resultado['sector'] : null;
    }*/
}
