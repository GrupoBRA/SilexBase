<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use OnyxERP\Core\Application\Service\Auth\JWTWrapper;

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
    const EXPIRATION_SECONDS = 43200;

    /**
     *
     * @param string $message
     * @param string $exception
     * @throws Exceptio
     */
    private function exceptionRequest($message, $exception = 'Não foi possível decodificar o token de acesso!')
    {
        $this->app['monolog']->error($message);
        throw new \Exception($exception);
    }

    private function encode(array $dados, $secret)
    {
        return JWTWrapper::encode(
            [
                'expiration_sec' => self::EXPIRATION_SECONDS,
                'iss' => 'Onyxprev',
                'data' => $dados,
            ], $secret
        );
    }
    
    /**
     *
     * @param Application $app
     * @param array       $dados
     * @return string
     * @throws Exception
     */
    public function __construct(Application $app, array $dados)
    {
        parent::__construct($app);

        try {

            //Id da APP que assinou o Jwt
            $appId = $dados['app']['apikey'];

            //Dados da APP supra citada
            $dadosApp = $this->getDadosApp($appId);

            //Dados do novo Jwt a ser gerado
            $payload = $dados;

            //seta o resultado na response
            $this->response = $this->encode($payload, $dadosApp['data']['apiSecret']);
        } catch (Exception $e) {
            $message = sprintf('%s', $e->getMessage());
            $this->exceptionRequest($message);
        }
    }
    
    /**
     * @param string $appId raw
     *
     * @return array
     *
     * @throws \Exception em caso de receber um status diferente de 200 da AppAPI
     */
    public function getDadosApp($appId)
    {
        try {

            // check se existe arquivo
            $filename = \CONFIG_API_ROOT . '/json/apps/' . $appId . '.json';

            if (\file_exists($filename)) {
                return parent::getApp()['json']->readJsonToArray($filename);
            }

            $conf = [
                'timeout' => 5,
                'verify' => false,
                'connect_timeout' => 5
            ];

            $response = parent::getApp()['guzzle']->get(URL_APP_API . 'app/' . \base64_encode($appId) . '/', $conf);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($response->getBody(), true);

                parent::getApp()['json']->createJSON($responseObj, $filename);
                return $responseObj;
            }

        } catch (\Exception $e) {
            throw new \Exception('Falha ao recuperar a assinatura da app!');
        }
    }
}
