<?php

namespace OnyxERP\Core\Application\Service;

use Symfony\Component\HttpFoundation\Request;
use OnyxERP\Core\Application\Service\JwtAPI\CheckJWT;
use OnyxERP\Core\Application\Service\JwtAPI\Decode;
use Silex\Application;
use GuzzleHttp\Client;
use DomainException;
use Exception;
use const URL_JWT_API;

/**
 * JWTService.
 *
 * Classe de abstração do acesso a JwtAPI para geração, atualização e validação
 * do Json Web Token
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.6.0
 */
class JWTService extends BaseService
{
 
    /**
     *
     * @var Client Instância do Guzzle
     */
    private $guzzle;
    
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->guzzle = $app['guzzle'];
    }

    /**
     * Retorna os dados no payload do token informado em $jwt, se este for válido.
     *
     * @param string $jwt
     *            JSON Web Token
     *
     * @return array Dados do token decodificado
     */
    public function getJWTPayload($jwt)
    {
        $decode = (new Decode($this->app, $jwt))->getResponse();
        $payload = $this->obj2array($decode);

        return \is_array($payload['data']) ? $payload : false;
    }

    /**
     *
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
     *            Authorization Header
     *
     * @return string JSON Web Token tratado
     *
     * @throws DomainException caso $authorization seja omitido ou informado vazio
     */
    public function trataJWT($authorization)
    {
        list($jwt) = \sscanf($authorization, 'Bearer %s');
        if (!$jwt) {
            throw new DomainException('Token não informado');
        }

        return $jwt;
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
    
    /**
     * @param string $jwt
     *
     * @return bool true em caso de token válido e ainda ativo
     *
     * @throws Exception em caso de receber um status diferente de 200 da JwtAPI
     */
    public function checkJWT($jwt)
    {
        try {
            return (new CheckJWT($this->app, $jwt))->getResponse();
        } catch (Exception $e) {
            //repassando a exception
            throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
