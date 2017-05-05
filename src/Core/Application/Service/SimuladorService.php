<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \Silex\Application;
use \URL_SIMULADOR_API;

/**
 * SimuladorService
 *
 * PHP version 5.6
 *
 * @author rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.2.0
 */
class SimuladorService
{

    /**
     *
     * @var Application
     */
    private $app;

    /**
     *
     * @var string JWT Token
     */
    private $jwt;

    /**
     *
     * @var \GuzzleHttp\Client
     */
    private $guzzle;
    
    /**
     *
     * @var array
     */
    private $payload;

    /**
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        if(\defined('URL_SIMULADOR_API') === false){
            exit("O uso deste service requer a inicialização da constante URL_SIMULADOR_API.");
        }

        $this->app      = $app;
        $this->guzzle   = $app['guzzle'];
    }

    /**
     * 
     * @return array|boolean
     * @throws Exception
     */
    public function geral()
    {
        try {

            $url = URL_SIMULADOR_API . 'aposentadoria/simulacao/geral/tempo/';

            $confs = [
                'exceptions'    => false,
                'headers'       => [
                    'Authorization' => "Bearer ". $this->getJwt()
                ],
                'body' => \json_encode($this->getPayload())
            ];

            $response = $this->guzzle->post($url, $confs);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($response->getBody()->getContents(), true);

                return $responseObj['data'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível acessar a SimuladorAPI!!');
        }
    }

    public function getJwt()
    {
        return $this->jwt;
    }

    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }

    public function getPayload() 
    {
        return $this->payload;
    }

    public function setPayload(array $payload)
    {
        $this->payload = $payload;
        return $this;
    }
}
