<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use const \URL_JWT_API;

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

            $appId = \base64_decode($dados['apiKey']);

            $dadosApp = $this->getDadosApp($appId);

            $payload = $dados['data'];

            $this->response = $this->encode($payload, $dadosApp['data']['apiSecret']);
        } catch (Exception $e) {
            $message = sprintf('%s', $e->getMessage());
            $this->exceptionRequest($message);
        }
    }
}
