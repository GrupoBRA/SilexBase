<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use const \URL_JWT_API;

/**
 * Decode.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.13.0
 */
class Decode extends BaseService
{
    /**
     * Decodifica um JSON Web Token.
     *
     * @param string $jwt
     *            JSON Web Token
     *
     * @return array Dados do token decodificado
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function __construct(Application $app, $jwt)
    {
        try {
            parent::__construct($app);
            $response = $this->app['guzzle']->get(
                URL_JWT_API . 'decode/',
                [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwt
                ]
                    ]
            );

            if ($response->getStatusCode() !== 200) {
                $message = sprintf('%s - %s', $response->getStatusCode(), $response->getReasonPhrase());
                $this->app['monolog']->error($message);
                throw new Exception('Não foi possível decodificar o token de acesso!');
            }
            
            $responseObj = \json_decode($response->getBody(), true);

            $this->response = $responseObj['data'];
        } catch (Exception $e) {
            $this->app['monolog']->error($e->getTraceAsString());
            throw new Exception('Não foi possível decodificar o token de acesso!');
        }
    }
}
