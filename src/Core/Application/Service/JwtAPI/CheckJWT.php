<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use const \URL_JWT_API;

class CheckJWT extends BaseService
{
    /**
     *
     * @param string $jwt
     *
     * @return bool true em caso de token válido e ainda ativo
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function __construct(Application $app, $jwt)
    {
        try {
            parent::__construct($app, $jwt);
            $response = $this->app['guzzle']->get(
                URL_JWT_API . 'check/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwt
                ]
                    ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Não foi possível verificar o token de acesso!');
            }
            $responseObj = \json_decode($response->getBody(), true);

            $this->response = $responseObj['success'];
        } catch (Exception $e) {
            $this->app['monolog']->error($e);
            throw new Exception('Não foi possível verificar o token de acesso!');
        }
    }
}