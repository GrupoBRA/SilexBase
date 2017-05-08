<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use OnyxERP\Core\Application\Service\JwtAPI\CheckJWT;
use OnyxERP\Core\Application\Service\Auth\JWTWrapper;
use \Silex\Application;
use const \URL_JWT_API;

/**
 * Decode.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.13.0
 */
class Decode extends BaseService
{
    /**
     * Decodifica um JSON Web Token.
     *
     * @param string $jwt
     *            JSON Web Token
     *
     * @return array Dados do token decodificado
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function __construct(Application $app, $jwt)
    {
        parent::__construct($app, $jwt);

        try {

        $payload = \json_decode(\base64_decode(\explode('.', $jwt)[1]), true);
        $apiKey = $payload['data']['app']['apikey'];

        $listaApplication = $this->getDadosApp($apiKey);

        $this->response = JWTWrapper::decode($jwt, $listaApplication['data']['apiSecret'], true);
        } catch (\Exception $e){
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
