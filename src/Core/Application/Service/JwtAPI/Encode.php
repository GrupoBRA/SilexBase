<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use const \URL_JWT_API;

/**
 * Encode.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.2.0
 */
class Encode extends BaseService
{
    private $response;
    /**
     *
     * @param Application $app
     * @param array       $dados
     * @return string
     * @throws Exception
     */
    public function __construct(Application $app, array $dados)
    {
        try {
            parent::__construct($app);
            $response = $this->app['guzzle']->post(
                URL_JWT_API . 'encode/', [
                'body' => \json_encode(
                    [
                            'apiKey' => \base64_encode($dados['app']['apikey']),
                            'data' => $dados
                        ]
                )
                    ]
            );

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($response->getBody(), true);

                $this->response = $responseObj['access_token'];
            }
        } catch (Exception $e) {
            $this->app['monolog']->error($e);
            throw new Exception('Não foi possível obter o token de acesso!');
        }
    }
    
    public function getResponse()
    {
        return $this->response;
    }
}
