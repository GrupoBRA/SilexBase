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

    /**
     * Build payload from data
     *
     * @param array $dados
     * @return array
     * @throws \InvalidArgumentException
     */
    private function buildPayload(array $dados)
    {
        if (!isset($dados['app']['apikey'])) {
            throw new \InvalidArgumentException('apikey não informada!');
        }
        $body = [
            'apiKey' => \base64_encode($dados['app']['apikey']),
            'data' => $dados
        ];
        return [
            'body' => \json_encode($body)
        ];
    }
    /**
     *
     * @param string $message
     * @param string $exception
     * @throws Exception
     */
    private function exceptionRequest($message, $exception = 'Não foi possível decodificar o token de acesso!')
    {
        $this->app['monolog']->error($message);
        throw new \Exception($exception);
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
        try {
            parent::__construct($app);
            $payload = $this->buildPayload($dados);
            $response = $this->app['guzzle']->post(URL_JWT_API . 'encode/', $payload);

            if ($response->getStatusCode() !== 200) {
                $message = sprintf('%s - %s', $response->getStatusCode(), $response->getReasonPhrase());
                $this->exceptionRequest($message);
            }

            $responseObj = \json_decode($response->getBody(), true);

            $this->response = $responseObj['access_token'];
        } catch (Exception $e) {
            $message = sprintf('%s', $e->getMessage());
            $this->exceptionRequest($message);
        }
    }
    
}
