<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \Exception;
use OnyxERP\Core\Application\Service\BaseService;
use OnyxERP\Core\Application\Service\JwtAPI\Encode;
use OnyxERP\Core\Application\Service\JwtAPI\Decode;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

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
    public function __construct(Application $app, array $data, $jwt)
    {
        parent::__construct($app);
        try {

            $jwtPayload = $this->obj2array((new Decode($app, $jwt))->getResponse());

            $payload = \array_merge($jwtPayload['data'], $data);

            $this->response = (new Encode($app, $payload))->getResponse($payload);
        } catch (Exception $e) {
            throw new Exception('Não foi possível alterar o token de acesso!');
        }
    }

    /**
     * Converte um objeto stdClass em um array associativo.
     *
     * @param StdClass $obj
     *            Objeto a ser convertido
     *
     * @return array Objeto convertido em array
     */
    public function obj2array($obj)
    {
        return \json_decode(\json_encode($obj), true);
    }
}
