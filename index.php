<?php

/**
 * Bootstrap.
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
$app = require_once __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SocialAPI\Core\Application\Service\JWTService;
use SocialAPI\Core\Application\PessoaFisicaControllerProvider;

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
        } catch (\Exception $e) {
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
$app->run();
