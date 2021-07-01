<?php

error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Http\UploadedFile;

require __DIR__ . '/../vendor/autoload.php';
//Mis requires
require __DIR__ . '/entidades/persona.php';
require __DIR__ . '/entidades/usuario.php';
require __DIR__ . '/entidades/rubro.php';
require __DIR__ . '/controladores/UsuarioController.php';
require __DIR__ . '/controladores/PublicacionController.php';
require __DIR__ . '/helper/utilidades.php';
require __DIR__ . '/datos/accesoBD.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

// Habilitar CORS
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    // $routeContext = RouteContext::fromRequest($request);
    // $routingResults = $routeContext->getRoutingResults();
    // $methods = $routingResults->getAllowedMethods();
    
    $response = $handler->handle($request);
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get, POST, post');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    // Optional: Allow Ajax CORS requests with Authorization header
    // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->POST('/NuevoUsuario[/]', \UsuarioController::class . ':GuardarUsuario');
$app->POST('/BuscarUsuario[/]', \UsuarioController::class . ':BuscarUsuario');
$app->post('/BajaUsuario[/]', \UsuarioController::class . ':BorrarUsuario');
$app->post('/PedirRubros[/]', \Rubro::class . ':TraerRubros');
$app->post('/FiltrarPublicaciones[/]', \PublicacionController::class . ':FiltrarRubros');

$app->run();