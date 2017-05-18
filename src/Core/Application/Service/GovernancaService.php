<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \Silex\Application;
use \URL_GOVERNANCA_API;

/**
 * GovernancaService.
 *
 * PHP version 5.6
 *
 * @author rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.2.0
 */
class GovernancaService
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
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        if(\defined('URL_GOVERNANCA_API') === false){
            throw new Exception("Constante URL_GOVERNANCA_API não definida.");
        }
    }

    /**
     * Envia um post ao end-point v1/orgao-entidade/ em GovernancaAPI
     *
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function getOrgaoEntidade(array $payload)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'body' => \json_encode($payload),
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->post(URL_GOVERNANCA_API . 'orgao-entidade/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                return $responseObj['data'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível inserir uma nova PF em SocialAPI!');
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
}
