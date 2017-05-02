<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use \OnyxERP\Core\Application\Service\BaseService;
use OnyxERP\Core\Application\Service\JwtAPI\Encode;
use OnyxERP\Core\Application\Service\JwtAPI\CheckJWT;
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
    public function __construct(Application $app, array $data, array $jwtPayload, $jwt)
    {
        parent::__construct($app);
        try {

            $payload = \array_merge($jwtPayload['data'], $data['data']);

            $this->response = (new Encode($app, $payload))->getResponse($payload);
        } catch (Exception $e) {
            throw new Exception('Não foi possível alterar o token de acesso!');
        }
    }
}
