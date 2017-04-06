<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use const \URL_JWT_API;

class Push extends BaseService
{

    /**
     * Adiciona dados em $dados a um token já existente.
     *
     * @param array   $dados
     *            <code>
     *            //Dados a serem adicionados, para garantir a compatibilidade, deve seguir o formato
     *            $dados = [
     *            'key' => [
     *            'dados aqui'
     *            ]
     *            ];
     *            </code>
     * @param Request $request
     *
     * @return string Token atualizado
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function __construct(Application $app, array $dados, $jwt)
    {
        try {
            parent::__construct($app);
            $response = $this->app['guzzle']->post(
                URL_JWT_API . 'push/',
                [
                'body' => \json_encode(
                    [
                            'data' => $dados
                        ]
                ),
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwt
                ]
                    ]
            );

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Não foi possível alterar o token de acesso!');
            }

            $responseObj = \json_decode($response->getBody(), true);

            $this->response = $responseObj['access_token'];
        } catch (Exception $e) {
            throw new Exception('Não foi possível alterar o token de acesso!');
        }
    }
}
