<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \Silex\Application;
use const \URL_SOCIAL_API;

/**
 * SocialService.
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.2.0
 */
class SocialService
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
    }

    /**
     *
     * @param integer $pfCod
     * @return type
     * @throws Exception
     */
    public function buscaNomePessoaFisica($pfCod)
    {
        $this->app['monolog']->debug($pfCod);
        try {
            $guzzle = $this->app['guzzle'];
            $url = URL_SOCIAL_API . 'pessoa-fisica/' . $pfCod .'/';

            $response = $guzzle->get($url, [
                'exceptions'    => false,
                'headers'       => [
                    'Authorization' => "Bearer ". $this->getJwt()
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($response->getBody()->getContents(), true);

                return $responseObj['data'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível obter os dados!');
        }
    }

    /**
     *
     * @param string $needle
     * @return type
     * @throws Exception
     */
    public function searchPessoaFisica($needle)
    {
        $this->app['monolog']->debug($needle);
        try {
            $guzzle = $this->app['guzzle'];
            $url = URL_SOCIAL_API . 'pessoa-fisica/search/' . \preg_replace('/[\/]{1,}/', '', $needle) .'/';

            $response = $guzzle->get($url, [
                'exceptions'    => false,
                'headers'       => [
                    'Authorization' => "Bearer ". $this->getJwt()
                ]
            ]);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);
            
            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                return $responseObj['data'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível obter os dados!');
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
