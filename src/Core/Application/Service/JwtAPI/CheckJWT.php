<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use OnyxERP\Core\Application\Service\Auth\JWTWrapper;
use \Silex\Application;
use const \URL_JWT_API;

/**
 * CheckJWT.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.6.0
 */
class CheckJWT extends BaseService
{
    /**
     *
     * @param string $jwt
     *
     * @return bool true em caso de token válido e ainda ativo
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function __construct(Application $app, $jwt)
    {
        parent::__construct($app, $jwt);
        $this->response = $this->checkJwt($jwt);
    }

    
    private function checkJwt($jwt)
    {
        try {

            $jwtExplode = \explode('.', $jwt);
            $payload    = \base64_decode($jwtExplode[1]);
            $json       = \json_decode($payload);

            if (isset($json->data->app->apikey) === false) {
                throw new Exception('Apikey não foi informada.');
            }
            $listaApplication = $this->getDadosApp($json->data->app->apikey);

            $this->getApp()['jwt'] = JWTWrapper::decode($jwt, $listaApplication['data']['apiSecret']);

            return true;
        } catch (\Exception $ex) {
            return "Token inválido ou expirado.";
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
            $filename = \CACHE_PATH . '/AppAPI/apps/' . $appId . '.json';

            if (\file_exists($filename)) {
                return parent::getApp()['json']->readJsonToArray($filename);
            }

            $conf = [
                'timeour' => 5,
                'verify' => false,
                'connec_timeout' => 5
            ];

            $response = parent::getApp()['guzzle']->get(URL_APP_API . 'app/' . \base64_encode($appId) . '/', $conf);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($response->getBody(), true);

                parent::getApp()['json']->createJSON($responseObj, $filename);
                return $responseObj['data'];
            }

        } catch (\Exception $e) {
            throw new \Exception('Falha ao recuperar os dados da app!');
        }
    }
}
