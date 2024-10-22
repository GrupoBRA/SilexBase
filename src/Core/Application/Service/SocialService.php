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
                
                return (isset($responseObj['data']) ? $responseObj['data'] : $responseObj);
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
                return ['status' => $response->getStatusCode()];
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
    
    /**
     * 
     * @param type $pfCod
     * @return array
     * @throws Exception
     */
    public function getPessoaFisica($pfCod) 
    {
        try {

            $url = URL_SOCIAL_API . 'pessoa-fisica/' . $pfCod .'/';
            $this->app['monolog']->debug('requisição HTTP para: '. $url);

            //nome do arquivo em cache
            $filename = \CACHE_PATH . '/SocialAPI/json/pf/' . $pfCod . '.json';

            //verifica se já existe no cache
            if (\file_exists($filename)) {
                $this->app['monolog']->debug($pfCod .' recuperado do cache');
                return $this->app['json']->readJsonToArray($filename);
            }

            $guzzle = $this->app['guzzle'];

            $response = $guzzle->get($url, [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions'    => false,
            ]);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                //salva no cache
                if(isset($responseObj['data']) === true){
                    $this->app['json']->createJSON($responseObj['data'], $filename);
                }

                return (isset($responseObj['data']) ? $responseObj['data'] : $responseObj);
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível obter os dados do pessoa física');
        }
    }
    
    /**
     * Envia uma requisição GET ao end-point responsável pela recuperação de 
     * informações pessoais de um usuário em SocialAPI
     *
     * @return mixed Array com a resposta ou Boolean false
     * @throws Exception
     */
    public function getInfoAccount($pfId)
    {
        try {

            //nome do arquivo em cache
            $filename = \CACHE_PATH . '/SocialAPI/json/info-account/' . $pfId . '.json';

            //verifica se já existe no cache
            if (\file_exists($filename)) {
                return $this->app['json']->readJsonToArray($filename);
            }

            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->get(URL_SOCIAL_API .'pessoa-fisica/info-account/'. $pfId .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                //salva no cache
                if(isset($responseObj['data']) === true){
                    $this->app['json']->createJSON($responseObj['data'], $filename);
                }

                return (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception("Falha ao acessar a SocialAPI.");
        }
    }
    
    public function getPfCodById(string $pfId)
    {
        try {

            //nome do arquivo em cache
            $filename = \CACHE_PATH . '/SocialAPI/json/pf-id-cod/' . $pfId . '.json';

            //verifica se já existe no cache
            if (\file_exists($filename)) {
                $pf = $this->app['json']->readJsonToArray($filename);
                return $pf['pf_cod'];
            }

            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->get(URL_SOCIAL_API .'pf/cod/'. $pfId .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                //salva no cache
                if(isset($responseObj['data']) === true){
                    $pfCod = $responseObj['data'];
                    $this->app['json']->createJSON(['pf_cod' => $pfCod, 'pf_id' => $pfId], $filename);
                }

                return $pfCod;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception("Falha ao acessar a SocialAPI.");
        }
    }

    public function getPfIdByCod(int $pfCod)
    {
        try {

            //nome do arquivo em cache
            $filename = \CACHE_PATH . '/SocialAPI/json/pf-cod-id/' . $pfCod . '.json';

            //verifica se já existe no cache
            if (\file_exists($filename)) {
                $pf = $this->app['json']->readJsonToArray($filename);
                return $pf['pf_id'];
            }

            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions' => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer '. $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->get(URL_SOCIAL_API .'pf/id/'. $pfCod .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                //salva no cache
                if(isset($responseObj['data']) === true){
                    $pfId = $responseObj['data'];
                    $this->app['json']->createJSON(['pf_cod' => $pfCod, 'pf_id' => $pfId], $filename);
                }

                return $pfId;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception("Falha ao acessar a SocialAPI.");
        }
    }
    

    /**
     * Envia uma requisição do tipo GET ao end-point em SocialAPI responsável 
     * pela listagem de documentos de uma pessoa física por tipo 
     * 
     * @param type $pfCod
     * @param type $tipo identidade|ctps|titulo
     * @return array
     * @throws Exception
     */
    public function getPfDocumentoTipo($pfCod, $tipo)
    {
        $this->app['monolog']->debug($pfCod .' - '. $tipo);
        try {

            $guzzle = $this->app['guzzle'];

            $url = URL_SOCIAL_API . 'pessoa-fisica/documentos/'. $pfCod .'/tipo/'. $tipo .'/';

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

                return (isset($responseObj['data']) ? $responseObj['data'] : $responseObj);
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível obter os dados!');
        }
    }

    /**
     * Envia uma requisição do tipo GET ao end-point em SocialAPI responsável 
     * pela listagem de todos os documentos de uma pessoa física
     * 
     * @param type $pfCod
     * @param type $tipo identidade|ctps|titulo
     * @return array
     * @throws Exception
     */
    public function getPfDocumentos($pfCod)
    {
        $this->app['monolog']->debug($pfCod);
        try {

            $guzzle = $this->app['guzzle'];

            $url = URL_SOCIAL_API . 'pessoa-fisica/documentos/'. $pfCod .'/';

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

                return (isset($responseObj['data']) ? $responseObj['data'] : $responseObj);
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
