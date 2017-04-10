<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use const \URL_JWT_API;

/**
 * CheckJWT.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.13.0
 */
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
            } elseif($response->getStatusCode() === 403) {
                throw new Exception("Token expirado ou inválido!");
            } 
            
            $responseObj = \json_decode($response->getBody(), true);

            $this->response = $responseObj['success'];
        } catch (Exception $e) {
            $this->app['monolog']->error($e);
            throw new Exception('Não foi possível verificar o token de acesso!');
        }
    }
}
