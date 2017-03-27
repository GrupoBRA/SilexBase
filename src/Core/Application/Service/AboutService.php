<?php

namespace OnyxERP\Core\Application\Service;

use Silex\Application;
use GuzzleHttp\Client;
use OnyxERP\Core\Application\Service\JWTService;

/**
 * RhService.
 *
 * Autenticação de aplicações no sistema
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.4.0
 */
class AboutService
{
    /**
 * @var string Request body
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
 * @var JWTService
*/
    private $jwtService;

    /**
     * [__construct description].
     *
     * @param \Silex\Application $app [description]
     */
    public function __construct(Application $app)
    {
        $this->app          = $app;
        $this->guzzle       = $app['guzzle'];
        $this->jwtService   = new JWTService($app);
    }

    public function getDadosApp()
    {
        $dadosJwt = $this->jwtService->getJWTPayload($this->getJwt());

        $appId  = \base64_encode($dadosJwt['data']['app']['apiId']);

        $dados = $this->get($appId);

        if (isset($dados['statusCode']) === true and $dados['statusCode'] === 200) {
            $dadosApp = $dados['response']['data'];

            if (isset($dadosApp['apiSecret']) === true) {
                unset($dadosApp['apiSecret']);
            }

            return $dadosApp;
        } else {
            return (object) [];
        }
    }
    
    /**
     *
     * @return array
     * @throws \Exception
     */
    public function get($appId)
    {
        try {
            $conf = [
                'body'          => $this->getPayload(),
                'exceptions'    => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->guzzle->get(\URL_APP_API .'app/'. $appId .'/', $conf);

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
     * @return type
     */
    public function getPayload()
    {
        return $this->payload;
    }
    /**
     *
     * @param type $payload
     * @return \RecadAPI\Core\Application\Service\DeviceService
     */
    public function setPayload($payload)
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
     * @param type $jwt
     * @return \RecadAPI\Core\Application\Service\DeviceService
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
}