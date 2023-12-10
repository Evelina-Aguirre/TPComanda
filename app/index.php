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
require_once './controllers/EncuestaController.php';
require_once './controllers/LoginController.php';
require_once './controllers/PDFController.php';
require_once './controllers/fpdfController.php';

require_once './middlewares/RegistroAccionesMiddleware.php';
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
    $group->get('/{nombre}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno') ->add(new GuardarUsuarioMiddleware())->add(new SocioMiddleware());
    $group->put('[/]', \UsuarioController::class . ':ModificarUno')->add(new SocioMiddleware());
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno')->add(new SocioMiddleware());
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/descargarCSV', \ProductoController::class . ':descargarProductosDesdeCSV');//->add(new SocioMiddleware());
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{nombre}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->post('/cargarCSV', \ProductoController::class . ':cargarProductosDesdeCSV');//->add(new SocioMiddleware());
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new MozoMiddleware())->add(new RegistroAccionesMiddleware());
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->put('/modificar', \PedidoController::class . ':ModificarUno');
    $group->get('/actualizarEstado/{idPedido}', \PedidoController::class . ':ActualizarEstadoPedidoMesa');
    $group->get('/actualizarHoraFinalización/{idPedido}', \PedidoController::class . ':ActualizarEstadoPedidoMesa');
    $group->get('/pendientes/{sector}', \PedidoController::class . ':listaPendientes');

});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('[/]', \MesaController::class . ':CargarUno');
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->put('[/]', \PedidoController::class . ':ModificarPedido');
    $group->get('/masusada', \MesaController::class . ':mesaMasUsada');

});

$app->get('/servirpedidos', \PedidoController::class . ':servirPedido');
$app->get('/cliente', \MesaController::class . ':consultarEstadoPedido');
$app->get('/cobrar/{idPedido}',  \MesaController::class . ':cobrar');
$app->get('/cerrarMesa/{idMesa}',  \MesaController::class . ':cerrarMesa');
$app->post('/encuesta',  \EncuestaController::class . ':CargarUno');
$app->get('/mejoresEncuestas',  \EncuestaController::class . ':TraerEncuestas');
$app->get('/estadisticas',  \EncuestaController::class . ':TraerEncuestas');

//CSV
$app->get('/descargarCSV', \ProductoController::class . ':descargarProductosDesdeCSV');
$app->post('/cargarCsv', \ProductoController::class . ':CargarUnoProductoCsv');

//PDF descargarProductosDesdeCSV
$app->post('/cargarPDF', \fpdfController::class . ':cargarPDF');
$app->get('/descargarPDF', \fpdfController::class . ':descargarFPDF');
$app->get('/mostrarPDF', \fpdfController::class . ':mostrarPDF');


//PEDIDOS CON/SIN DEMORA
$app->get('/pedidosConDemora',  \PedidoController::class . ':pedidosConDemora');
$app->get('/pedidosSinDemora',  \PedidoController::class . ':pedidosSinDemora');

//ENCUESTAS
$app->get('/encuestastotal', \EncuestaController::class . ':TraerTotalEncuestas');
$app->get('/encuestasEstadisticas', \EncuestaController::class . ':TraerEstadisticasEncuestas');

$app->post('/login', \LoginController::class . ':Ingresar');
$app->get('/cerrarSesion', \LoginController::class . ':Deslogear');


$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "TP COMANDA"));
    
    sleep(3); 

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});




$app->run();
