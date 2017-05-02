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
        try {
        $payload = \json_decode(\base64_decode(\explode('.', $jwt)[1]), true);
        $apiKey = $payload['data']['app']['apikey'];

        $listaApplication = (new CheckJWT($app, $jwt))->getDadosApp($apiKey);

        $this->response = JWTWrapper::decode($jwt, $listaApplication['data']['apiSecret'], true);
        } catch (\Exception $e){
            return "Token inválido ou expirado.";
        }
    }
}
