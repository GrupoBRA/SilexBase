<?php
/**
 * Bootstrap.
 *
 * An instance of starting of a computer; a boot.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version   1.0.0
 */
date_default_timezone_set('America/Recife');
ini_set('display_errors', 1); // don't show any errors...
error_reporting(E_ALL); // ...but do log them

use \Silex\Application;
use \Silex\Provider\ServiceControllerServiceProvider;
use \Silex\Provider\ValidatorServiceProvider;
use \Silex\Provider\MonologServiceProvider;
use \Symfony\Component\Debug\ExceptionHandler;
use \Symfony\Component\Debug\ErrorHandler;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \OnyxERP\Core\Application\Service\GuzzleServiceProvider;

$loader = require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$app = new Application();
$app['debug'] = true;

$app->register(new ServiceControllerServiceProvider());

$app->register(new ValidatorServiceProvider());

/**
 * GuzzleServiceProvider
 */
$app['guzzle.timeout'] = 1.0;
$app->register(new GuzzleServiceProvider(), array(
    'guzzle.timeout' => 3.14,
    'guzzle.request_options' => [
        'auth' => [
            'admin',
            'admin'
        ]
    ]
));

/**
 * MonologServiceProvider
 */
if ($app['debug']) {
    $app->register(new MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/log/development.log'
    ));
}

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    // ... logic to handle the error and return a Response
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }
    return new Response($message);
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

include_once './config/urls_apis.php';
return $app;
