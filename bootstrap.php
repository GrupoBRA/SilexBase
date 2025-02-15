<?php

/**
 * Bootstrap.
 *
 * An instance of starting of a computer; a boot.
 *
 * PHP version 5.6
 *
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version   1.7.1
 */
date_default_timezone_set('America/Recife');
ini_set('display_errors', 1); // don't show any errors...
error_reporting(E_ALL); // ...but do log them
set_time_limit(300);

use \Monolog\Handler\RotatingFileHandler;
use \Monolog\Logger;
use \OnyxERP\Core\Application\Service\GuzzleServiceProvider;
use \OnyxERP\Core\Application\Service\JSONServiceProvider;
use \OnyxERP\Core\Application\Service\JWTService;
use \Silex\Application;
use \Silex\Provider\MonologServiceProvider;
use \Silex\Provider\ServiceControllerServiceProvider;
use \Silex\Provider\ValidatorServiceProvider;
use \Symfony\Component\Debug\ErrorHandler;
use \Symfony\Component\Debug\ExceptionHandler;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

chdir(__DIR__);

foreach (array(__DIR__ . '/../../../config/urls_apis.php', __DIR__ . '/../../config/urls_apis.php', __DIR__ . '/../config/urls_apis.php', __DIR__ . '/config/urls_apis.php') as $config) {
    if (file_exists($config)) {
        if (!defined('CONFIG_URL_APIS')) {
            define('CONFIG_URL_APIS', $config);
        }
        break;
    }
}

if (!defined('CONFIG_URL_APIS')) {
    exit('You need to set up the project dependencies using config/url_apis.php:' . PHP_EOL . PHP_EOL);
}

unset($config);

include_once CONFIG_URL_APIS;

foreach (array(__DIR__ . '/../../../autoload.php', __DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php') as $file) {
    if (file_exists($file)) {
        if (!defined('COMPOSER_AUTOLOAD')) {
            define('COMPOSER_AUTOLOAD', $file);
        }

        break;
    }
}

unset($file);

if (!defined('COMPOSER_AUTOLOAD')) {
    exit('You need to set up the project dependencies using Composer:' . PHP_EOL . PHP_EOL .
        '    composer install' . PHP_EOL . PHP_EOL .
        'You can learn all about Composer on https://getcomposer.org/.' . PHP_EOL
    );

    die(1);
}

$loader = require COMPOSER_AUTOLOAD;

foreach (array(__DIR__ . '/../../../config/routes.php', __DIR__ . '/../../config/routes.php', __DIR__ . '/../config/routes.php', __DIR__ . '/config/routes.php') as $route) {
    if (file_exists($route)) {
        if (!defined('CONFIG_ROUTES')) {
            define('CONFIG_ROUTES', $route);
        }
        break;
    }
}
if (!defined('CONFIG_ROUTES')) {
    exit('You need to set up the project dependencies using config/routes.php:' . PHP_EOL . PHP_EOL);
}

unset($route);

//Path absoluto da raíz da api.
if (\defined('CONFIG_API_ROOT') === false) {
    \define('CONFIG_API_ROOT', \realpath(__DIR__ .'../../'));
    \define('CACHE_PATH', \realpath(CONFIG_API_ROOT .'/Cache/'));
}

//Constante com o path do cache compartilhado das APIs, no mesmo nível delas
if (\defined('CACHE_PATH') === false) {
    \define('CACHE_PATH', \realpath(\CONFIG_API_ROOT .'/../Cache/'));
}


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
        'exceptions' => false
    ]
));
/**
 * MonologServiceProvider
 */
if ($app['debug']) {
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/../../../log/development.log'
    ));

    $app->extend('monolog', function ($monolog, $app) {
        $monolog->pushHandler(new RotatingFileHandler($app['monolog.logfile'], 3, Logger::DEBUG));
        return $monolog;
    });
}

$app->error(function (Exception $e, Request $request, $code) use ($app) {
    $response = [];
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

$listaRouteLiberada = include_once CONFIG_ROUTES;
/*
 * Não remover esse trecho
 */
$app->before(function (Request $request, Application $app) use ($listaRouteLiberada) {
    $route = $request->get('_route');

    //adiciona a rota OPTION / à lista branca de todas as APIs.
    \array_push($listaRouteLiberada, 'OPTIONS_url');

    if (!in_array($route, $listaRouteLiberada)) {
        try {

            $jwtService = new JWTService($app);

            $jwt        = $jwtService->getAuthorizationJWT($request);
            $jwtData    = $jwtService->getJWTPayload($jwt);
            $checked    = \is_array($jwtData);

            $app['jwt.token'] = null;

            if ($checked) {
                $app['jwt.token']   = $jwt;
                $app['jwt.payload'] = $jwtData;
            } else {
                throw new \Exception("Token inválido ou expirado.");
            }
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => false,
                'error' => $e->getMessage(),
            ], 403);
        }
    }
});

$app->after(function (Request $request, Response $response) {

    $headers = $request->headers;

    if(!empty($headers->get('user-agent'))){
        if(\preg_match('/^[Mozilla\/]{8}/', $headers->get('user-agent'))){
            $simpleResponse = \json_decode($response->getContent());
            if(\json_last_error() < 1){
                $response->setContent(\json_encode($simpleResponse, \JSON_PRETTY_PRINT));
            }
        }
    }
    
    $response->headers->set('Accept-Encoding', 'GZIP');
    $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
    $response->headers->set('Access-Control-Allow-Credentials', true);
    $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, Origin, X-GitHub-OTP, X-Requested-With');
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,HEAD,OPTIONS');
});

/*
 * Implementação do CORS https://github.com/BRAConsultoria/OnyxERP/issues/74
 */
$app->match('{url}', function ($url) {
            return new JsonResponse([
                'status' => 'Ok'
                    ], 200, [
                'WWW-Authenticate' => 'Bearer'
            ]);
        })
        ->assert('url', '.*')
        ->method('OPTIONS');

return $app;
