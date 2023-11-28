<?php
//php -S localhost:999 -t app
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
//require_once './db/AccesoDatos.php';
require_once './middlewares/LoggerMiddleware.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/GuardarUsuarioMiddleware.php';
require_once './middlewares/SocioMiddleware.php';
require_once './middlewares/MozoMiddleware.php';
require_once './middlewares/ClienteMiddleware.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
// Set base path
$app->setBasePath('/TPComanda/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

/// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno') ->add(new GuardarUsuarioMiddleware())->add(new SocioMiddleware());
    $group->put('[/]', \UsuarioController::class . ':ModificarUno')->add(new SocioMiddleware());
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno')->add(new SocioMiddleware());
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/exportarCSV', \ProductoController::class . ':ExportarCsv')->add(new SocioMiddleware());
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{nombre}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->post('/importarCSV', \ProductoController::class . ':ImportarCsv')->add(new SocioMiddleware());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('[/]', \PedidoController::class . ':CargarUno');
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->put('/modificar', \PedidoController::class . ':ModificarPedido');
    $group->get('/actualizarEstado/{idPedido}', \PedidoController::class . ':ActualizarEstadoPedidoMesa');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('[/]', \MesaController::class . ':CargarUno');
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->put('[/]', \PedidoController::class . ':ModificarPedido');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "TP COMANDA"));
    
    // Pausa para probar el middleware (5 segundos)
    sleep(3);
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
})->add(new LoggerMiddleware())->add(\LoggerMiddleware::class . ':VerificarRol');




$app->run();
