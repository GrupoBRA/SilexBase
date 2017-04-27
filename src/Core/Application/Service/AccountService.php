<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \Silex\Application;

/**
 * AccountService.
 *
 * PHP version 5.6
 *
 * @author rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.2.0
 */
class AccountService
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
     * @var array
     */
    private $payload;

    /**
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Envia um post ao end-point singup em AccountAPI
     *
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function signup()
    {
        try {

            $conf = [
                'connect_timeout' => 25,
                'timeout' => 25,
                'exceptions' => false
            ];

            if (!empty($this->getPayload())) {
                $conf['body'] = \json_encode($this->getPayload());
            }

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->post(\URL_ACCOUNT_API . 'signup/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);
                $data = (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                $data = [];
            }

            return [
                'status'    => $response->getStatusCode(),
                'response'  => $data
            ];
        } catch (Exception $e){
            throw new \RuntimeException($e->getMessage());
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
