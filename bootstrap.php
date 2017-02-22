<?php

/**
 * Bootstrap.
 *
 * An instance of starting of a computer; a boot.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version   1.0.9
 */
date_default_timezone_set('America/Recife');
ini_set('display_errors', 1); // don't show any errors...
error_reporting(E_ALL); // ...but do log them
set_time_limit(300);

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use OnyxERP\Core\Application\Service\GuzzleServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

chdir(__DIR__);

include_once './config/urls_apis.php';

$loader = require __DIR__ . '/vendor/autoload.php';

$app = new Application();
$app['debug'] = true;
/**
 * ServiceControllerServiceProvider
 */
$app->register(new ServiceControllerServiceProvider());
/**
 * ValidatorServiceProvider
 */
$app->register(new ValidatorServiceProvider());
/**
 * JSONServiceProvider
 */
$app->register(new JSONServiceProvider());
/**
 * GuzzleServiceProvider
 */
$app['guzzle.timeout'] = 1.0;
$app->register(new GuzzleServiceProvider(), array(
    'guzzle.timeout' => 3.14,
    'guzzle.request_options' => [
        'exceptions' => false,
    ]
));
/**
 * MonologServiceProvider
 */
if ($app['debug']) {
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => './log/development.log'
    ));

    $app->extend('monolog', function ($monolog, $app) {
        $monolog->pushHandler(new RotatingFileHandler($app['monolog.logfile'], 3, Logger::DEBUG));
        return $monolog;
    });
}

$app->error(function (Exception $e, Request $request, $code) use ($app) {
    $response['status'] = false;

    if ($app['debug']) {
        $response['exceptions']['message'] = $e->getMessage();
        $response['exceptions']['file'] = $e->getFile();
        $response['exceptions']['line'] = $e->getLine();
        $response['exceptions']['trace'] = $e->getTrace();

        $response['request']['method'] = $request->getMethod();
        $response['request']['path_info'] = $request->getPathInfo();
        $response['request']['content'] = $request->getContent();        
    }
    // ... logic to handle the error and return a Response
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }
    $response['message'] = $message;
    return new Response(\json_encode($response), 404);
});

ErrorHandler::register();
ExceptionHandler::register();

$app->view(function (array $controllerResult, Request $request) use ($app) {
    $acceptHeader = $request->headers->get('Accept');
    $bestFormat = $app['negotiator']->getBestFormat($acceptHeader, array(
        'json',
        'xml'
    ));
    if ('json' === $bestFormat) {
        return new JsonResponse($controllerResult);
    }
    if ('xml' === $bestFormat) {
        return $app['serializer.xml']->renderResponse($controllerResult);
    }
    return $controllerResult;
});

/*
 * Não remover esse trecho
 */
$app->before(function (Request $request, Application $app) {
    $route = $request->get('_route');
    $listaRouteLiberada = array(
        'OPTIONS_url',
        'GET_v1_pessoa_fisica_',
        'GET_v1_pessoa_fisica_id',
        'GET_v1_pessoa_fisica_id_',
    );

    if (!in_array($route, $listaRouteLiberada)) {
        try {
            $jwtService = new JWTService($app);

            return $jwtService->checkJWT($jwtService->getAuthorizationJWT($request));
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
    }
});

$app->after(function (Request $request, Response $response) {
    if ($request) {
    }
    $response->headers->set('Accept-Encoding', 'GZIP');
    $response->headers->set('Content-Type', 'application/json');
    $response->headers->set('Content-Type', 'UTF-8');
    $response->headers->set('Access-Control-Allow-Credentials', true);
    $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, Origin, X-GitHub-OTP, X-Requested-With');
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,HEAD,DELETE,PUT,OPTIONS');
});

/*
 * Implementação do CORS https://github.com/BRAConsultoria/OnyxERP/issues/74
 */
$app->match('{url}', function ($url) {
    return new JsonResponse([
        'status' => 'Ok',
    ], 200, [
        'WWW-Authenticate' => 'Bearer',
    ]);
})
    ->assert('url', '.*')
    ->method('OPTIONS');
/*
 * Recursos Habilitados
 *
 * basta adiciona uma nova linha para disponibilização de novos recursos REST API
 */
$app->mount('/v1/pessoa-fisica', new PessoaFisicaControllerProvider());
return $app;
