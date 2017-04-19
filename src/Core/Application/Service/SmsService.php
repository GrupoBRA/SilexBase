<?php

namespace OnyxERP\Core\Application\Service;

use Silex\Application;
use GuzzleHttp\Client;
use \URL_SMS_API;

/**
 * AccountService.
 *
 * Autenticação de aplicações no sistema
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.3.0
 */
class SmsService
{
    /**
     * @var array Request body
     */
    private $payload;

    /**
     * @var string Json Web Token
     */
    private $jwt;

    /**
     * @var Client Instância do Guzzle
     */
    private $guzzle;
    
    /**
     *
     * @var Application
     */
    private $app;

    /**
     * [__construct description].
     *
     * @param \Silex\Application $app [description]
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->guzzle = $app['guzzle'];
    }

    /**
     *
     * @return array
     * @throws \Exception
     */
    public function enviarSms($pfCod)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout'       => 10,
                'body'          => \json_encode($this->getPayload()),
                'exceptions'    => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->guzzle->post(URL_SMS_API .'enviar/'. $pfCod .'/', $conf);

            return [
                'response'      => \json_decode($response->getBody()->getContents(), true),
                'statusCode'    => $response->getStatusCode()
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
    /**
     *
     * @param array $payload
     * @return array
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
        return $this;
    }
    /**
     *
     * @return type
     */
    public function getJwt()
    {
        return $this->jwt;
    }
    /**
     *
     * @param string $jwt
     * @return SmsService
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
}
