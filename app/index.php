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
require __DIR__ . '/entidades/publicacion.php';
require __DIR__ . '/controladores/UsuarioController.php';
require __DIR__ . '/controladores/PublicacionController.php';
require __DIR__ . '/controladores/RubroController.php';
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
    $response = $response->withHeader('Access-Control-Allow-Methods', 'get, post');
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    // Optional: Allow Ajax CORS requests with Authorization header
    // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->group('/Usuario', function (RouteCollectorProxy $group) {
    $group->POST('/Buscar[/]', \UsuarioController::class . ':BuscarUsuario');
    $group->POST('/Nuevo[/]', \UsuarioController::class . ':GuardarUsuario');
    $group->POST('/Baja[/]', \UsuarioController::class . ':BorrarUsuario');
});

$app->group('/Rubro', function (RouteCollectorProxy $group) {
    $group->POST('/Listar[/]', \RubroController::class . ':ListarRubros');
    $group->GET('/Listar[/]', \RubroController::class . ':ListarRubros');
});

$app->group('/Publicacion', function (RouteCollectorProxy $group) {
    $group->POST('/Filtrar/Rubro/todas[/]', \PublicacionController::class . ':FiltrarTodasPorRubro');
    $group->POST('/Filtrar/Rubro[/]', \PublicacionController::class . ':FiltrarRubros');
    $group->POST('/EditarEDI[/]', \PublicacionController::class . ':EditarPublicacionEDI');
    $group->POST('/Bloquear[/]', \PublicacionController::class . ':BloquearPublicacion');
});

$app->run();