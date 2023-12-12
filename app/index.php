<?php
//php -S localhost:999 -t app
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
require_once './controllers/EstadisticasController.php';

require_once './middlewares/RegistroAccionesMiddleware.php';
require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/GuardarUsuarioMiddleware.php';
require_once './middlewares/SocioMiddleware.php';
require_once './middlewares/MozoMiddleware.php';
require_once './middlewares/ClienteMiddleware.php';
require_once './middlewares/EmpleadoMiddleware.php';
require_once './middlewares/VerificarSectorMiddleware.php';
require_once'./middlewares/LogueoMiddleware.php';


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
    $group->get('/listarUsuarios', \UsuarioController::class . ':TraerTodos');
    $group->get('/mostrarUsuario/{nombre}', \UsuarioController::class . ':TraerUno');
    $group->post('/crearUsuario', \UsuarioController::class . ':CargarUno') ->add(new GuardarUsuarioMiddleware())->add(new SocioMiddleware());
    $group->put('/modificarUsuario', \UsuarioController::class . ':ModificarUno')->add(new SocioMiddleware());
    $group->delete('/bajaUsuario', \UsuarioController::class . ':BorrarUno')->add(new SocioMiddleware());
})->add(new RegistroAccionesMiddleware());

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/descargarCSV', \ProductoController::class . ':descargarProductosDesdeCSV')->add(new SocioMiddleware());
    $group->get('/listarProductos', \ProductoController::class . ':TraerTodos');
    $group->get('/mostrarProducto/{nombre}', \ProductoController::class . ':TraerUno');
    $group->post('/cargarProducto', \ProductoController::class . ':CargarUno')->add(new SocioMiddleware());
    $group->post('/cargarCSV', \ProductoController::class . ':cargarProductosDesdeCSV')->add(new SocioMiddleware());
    $group->put('/modificarProducto', \ProductoController::class . ':ModificarUno')->add(new SocioMiddleware());
    $group->delete('/borrarProducto', \ProductoController::class . ':BorrarUno')->add(new SocioMiddleware());
})->add(new RegistroAccionesMiddleware());

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/cargarPedido', \PedidoController::class . ':CargarUno')->add(new MozoMiddleware());
    $group->post('/relacionarFotoAPedido', \PedidoController::class . ':relacionarFoto');
    $group->get('/listarPedidos', \PedidoController::class . ':TraerTodos')->add(new SocioMiddleware());
    $group->put('/modificar', \PedidoController::class . ':ModificarUno')->add(new VerificarSectorMiddleware());
    $group->delete('/cancelarPedido', \PedidoController::class . ':BorrarUno');
    $group->get('/actualizarEstado/{idPedido}', \PedidoController::class . ':ActualizarEstadoPedidoMesa');
    $group->get('/actualizarHoraFinalización/{idPedido}', \PedidoController::class . ':ActualizarEstadoPedidoMesa');
    $group->get('/pendientes/{sector}', \PedidoController::class . ':listaPendientes')->add(new VerificarSectorMiddleware())->add(new EmpleadoMiddleware());
    $group->get('/listarProductosEnPedidos', \PedidoController::class . ':listaProductosPedidos');

})->add(new RegistroAccionesMiddleware());

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/cargarMesa', \MesaController::class . ':CargarUno');
    $group->get('/listarMesas', \MesaController::class . ':TraerTodos');
    $group->put('/modificarMesa', \MesaController::class . ':ModificarUno');
    $group->delete('/borrarMesa', \MesaController::class . ':BorrarUno');
    $group->get('/masusada', \MesaController::class . ':mesaMasUsada')->add(new SocioMiddleware());

})->add(new RegistroAccionesMiddleware());

$app->get('/servirpedidos', \PedidoController::class . ':servirPedido')->add(new MozoMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/cobrar/{idMesa}',  \MesaController::class . ':cobrar')->add(new MozoMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/cerrarMesa/{idMesa}',  \MesaController::class . ':cerrarMesa')->add(new RegistroAccionesMiddleware());

//ENCUESTA
$app->post('/encuesta/cargarEncuesta',  \EncuestaController::class . ':CargarUno')->add(new ClienteMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/mejoresEncuestas',  \EncuestaController::class . ':TraerEncuestas')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/estadisticas',  \EncuestaController::class . ':TraerEncuestas')->add(new RegistroAccionesMiddleware());

//CLIENTE CONSULTA DEMORA
$app->get('/consultarDemora', \MesaController::class . ':consultarEstadoPedido')->add(new ClienteMiddleware())->add(new RegistroAccionesMiddleware());


//CSV
$app->get('/descargarCSV', \ProductoController::class . ':descargarProductosDesdeCSV')->add(new RegistroAccionesMiddleware());
$app->post('/cargarCsv', \ProductoController::class . ':CargarUnoProductoCsv')->add(new RegistroAccionesMiddleware());

//PDF 
$app->post('/cargarPDF', \fpdfController::class . ':cargarPDF')->add(new RegistroAccionesMiddleware());
$app->get('/descargarPDF', \fpdfController::class . ':descargarFPDF')->add(new RegistroAccionesMiddleware());
$app->get('/mostrarPDF', \fpdfController::class . ':mostrarPDF')->add(new RegistroAccionesMiddleware());
$app->get('/descargarPDFLogo', \fpdfController::class . ':descargarPDFLogo')->add(new RegistroAccionesMiddleware());


//PEDIDOS CON/SIN DEMORA
$app->get('/pedidosConDemora',  \PedidoController::class . ':pedidosConDemora')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/pedidosSinDemora',  \PedidoController::class . ':pedidosSinDemora')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());

//ENCUESTAS
$app->get('/encuestastotal', \EncuestaController::class . ':TraerTotalEncuestas')->add(new RegistroAccionesMiddleware());
$app->get('/encuestasEstadisticas', \EncuestaController::class . ':TraerEstadisticasEncuestas')->add(new RegistroAccionesMiddleware());


//ESTADISTICAS
$app->get('/operacionesPorSector', \EstadisticasController::class . ':TraerTotalOperaciones')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/cantidadOperacionesPorSector', \EstadisticasController::class . ':TraerCantidadTotalOperaciones')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/operacionesPorEmpleadoPorSector', \EstadisticasController::class . ':TraerOperacionesPorEmpleadoPorSector')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/masVendidoAMenosVendido', \EstadisticasController::class . ':TraerMasVendidoAMenosVendido')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/logIngresosSistema', \EstadisticasController::class . ':TraerlogIngresosSistema')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->get('/cobroMasCaroMasBarato', \EstadisticasController::class . ':TraercobroMasCaroMasBarato')->add(new SocioMiddleware())->add(new RegistroAccionesMiddleware());
$app->post('/facturoEntreDosFechas', \EstadisticasController::class . ':TraerfacturoEntreDosFechas')/*->add(new SocioMiddleware())*/->add(new RegistroAccionesMiddleware());



$app->post('/login', \LoginController::class . ':Ingresar')->add(new LogueoMiddleware());
$app->get('/cerrarSesion', \LoginController::class . ':Deslogear');


$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "TP COMANDA"));
    
    sleep(3); 

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});




$app->run();
