<?php

namespace SocialAPI\Core\Application\Service;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * JWTService.
 *
 * Classe de abstração do acesso a JwtAPI para geração, atualização e validação
 * do Json Web Token
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
class JWTService
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Solicita a geração de um novo JWT à API responsável, com os dados informado em $dados.
     *
     * @param array $dados
     *                     Dados a serem informados no payload do token
     *
     * @return string Token gerado
     *
     * @throws \Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function encode(array $dados)
    {
        try {
            $response = $this->getApp()['guzzle']->post(\URL_JWT_API.'encode/', [
                'body' => \json_encode([
                    'apiKey' => \base64_encode($dados['app']['apikey']),
                    'data' => $dados,
                ]),
            ]);

            if ($response->getStatusCode() === '200') {
                $responseObj = \json_decode($response->getBody(), true);

                return $responseObj['access_token'];
            }
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível obter o token de acesso!');
        }
    }

    /**
     * Decodifica um JSON Web Token.
     *
     * @param string $jwt
     *                    JSON Web Token
     *
     * @return array Dados do token decodificado
     *
     * @throws \Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function decode($jwt)
    {
        try {
            $response = $this->getApp()['guzzle']->get(\URL_JWT_API.'decode/', [
                'headers' => [
                    'Authorization' => 'Bearer '.$jwt,
                ],
            ]);

            if ($response->getStatusCode() === '200') {
                $responseObj = \json_decode($response->getBody(), true);

                return $responseObj['data'];
            }
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível decodificar o token de acesso!');
        }
    }

    /**
     * Adiciona dados em $dados a um token já existente.
     *
     * @param array   $dados
     *                         <code>
     *                         //Dados a serem adicionados, para garantir a compatibilidade, deve seguir o formato
     *                         $dados = [
     *                         'key' => [
     *                         'dados aqui'
     *                         ]
     *                         ];
     *                         </code>
     * @param Request $request
     *
     * @return string Token atualizado
     *
     * @throws \Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function push(array $dados, $jwt)
    {
        try {
            $response = $this->getApp()['guzzle']->post(\URL_JWT_API.'push/', [
                'body' => \json_encode([
                    'data' => $dados,
                ]),
                'headers' => [
                    'Authorization' => 'Bearer '.$jwt,
                ],
            ]);

            if ($response->getStatusCode() === '200') {
                $responseObj = \json_decode($response->getBody(), true);

                return $responseObj['access_token'];
            }
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível alterar o token de acesso!');
        }
    }

    /**
     * @param string $jwt
     *
     * @return bool true em caso de token válido e ainda ativo
     *
     * @throws \Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function checkJWT($jwt)
    {
        try {
            $response = $this->getApp()['guzzle']->get(\URL_JWT_API.'check/', [
                'headers' => [
                    'Authorization' => 'Bearer '.$jwt,
                ],
            ]);

            if ($response->getStatusCode() === '200') {
                $responseObj = \json_decode($response->getBody(), true);

                return $responseObj['success'];
            }
        } catch (\Exception $e) {
            throw new \Exception('Não foi possível verificar o token de acesso!');
        }
    }

    /**
     * Retorna os dados no payload do token informado em $jwt, se este for válido.
     *
     * @param string $jwt
     *                    JSON Web Token
     *
     * @return array Dados do token decodificado
     */
    public function getJWTPayload($jwt)
    {
        $payload = $this->obj2array($this->decode($jwt));

        return \is_array($payload['data']) ? $payload : false;
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @throws DomainException
     */
    public function getAuthorizationJWT(Request $request)
    {
        $authorization = $request->headers->get('Authorization');

        return $this->trataJWT($authorization);
    }

    /**
     * Extrai o prefixo Bearer do token.
     *
     * @param string $authorization
     *                              Authorization Header
     *
     * @return string JSON Web Token tratado
     *
     * @throws \DomainException caso $authorization seja omitido ou informado vazio
     */
    public function trataJWT($authorization)
    {
        list($jwt) = \sscanf($authorization, 'Bearer %s');
        if (!$jwt) {
            throw new \DomainException('Token não informado');
        }

        return $jwt;
    }

    /**
     * Converte um objeto stdClass em um array associativo.
     *
     * @param StdClass $obj
     *                      Objeto a ser convertido
     *
     * @return array Objeto convertido em array
     */
    public function obj2array($obj)
    {
        return \json_decode(\json_encode($obj), true);
    }

    /**
     * @return the Application
     */
    public function getApp()
    {
        return $this->app;
    }
}
