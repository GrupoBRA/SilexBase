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
     * @return mixed
     * @throws Exception
     */
    public function searchPessoaFisica($needle)
    {
        $this->app['monolog']->debug($needle);
        try {
            $guzzle = $this->app['guzzle'];
            $url = URL_SOCIAL_API . 'pessoa-fisica/search/' . \preg_replace('/[\/]{1,}/', '', $needle) .'/';

            $response = $guzzle->get($url, [
                'connect_timeout' => 10,
                'timeout' => 10,
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

    /**
     * Envia um post ao end-point responsável pela inserção de uma nova pessoa em Social
     *
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function inserir(array $payload)
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

            $response = $this->app['guzzle']->post(URL_SOCIAL_API . 'pessoa-fisica/inserir', $conf);

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

    /**
     * Envia um PUT ao end-point responsável pela atualização de informações de
     * de contato em SocialAPI
     *
     * @return mixed Array com a resposta ou Boolean false
     * @throws Exception
     */
    public function updatePfTable($table, $id, $data)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'body' => \json_encode($data),
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->put(URL_SOCIAL_API .'pessoa-fisica/'. $table .'/'. $id .'/', $conf);
            
            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                return (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception("Falha ao atualizar $table!");
        }
    }
    
    /**
     * Envia um PATCH ao end-point responsável pela atualização de informações de
     * verificadas em SocialAPI
     *
     * @return mixed Array com a resposta ou Boolean false
     * @throws Exception
     */
    public function verif($id, $tipo, $info)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'body' => \json_encode(['info' => $info]),
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->patch(URL_SOCIAL_API .'pessoa-fisica/verif/'. $id .'/tipo/'. $tipo .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                return (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception("Falha ao acessar a SocialAPI.");
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
