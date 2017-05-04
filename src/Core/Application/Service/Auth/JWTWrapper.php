<?php

namespace OnyxERP\Core\Application\Service\Auth;

use Firebase\JWT\JWT;

/**
 * JWTWrapper.
 *
 * Gerenciamento de tokens JWT.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.6.0
 */
class JWTWrapper
{
    /**
     * Codifica o JWT.
     *
     * @param array  $options
     * <code>
     * $options = [
     *     'expiration_sec' => integer, // tempo de expiracao do token
     *     'iss' => string, // dominio, pode ser usado para descartar tokens de outros dominios
     *     'userdata' => mixed // dados do usuário logado
     *  ]
     * </code>
     * @param string $secret  Chave secreta para geração
     *
     * @return string A signed JWT
     */
    public static function encode(array $options, $secret = '04c255b7')
    {
        $issuedAt = time();
        $expire = $issuedAt + $options['expiration_sec']; // tempo de expiracao do token

        $tokenParam = [
            'iat' => $issuedAt,            // timestamp de geracao do token
            'iss' => $options['iss'],      // dominio, pode ser usado para descartar tokens de outros dominios
            'exp' => $expire,              // expiracao do token
            'nbf' => $issuedAt - 1,        // token nao eh valido Antes de
            'data' => $options['data'], // Dados do usuario logado
        ];

        return JWT::encode($tokenParam, base64_encode($secret));
    }

    /**
     * Decodifica o JWT.
     *
     * @param string $jwt    [description]
     * @param string $secret [description]
     *
     * @return urlsafeB64Decode The JWT's payload as a PHP object
     */
    public static function decode($jwt, $secret = '04c255b7')
    {
        return JWT::decode($jwt, \base64_encode($secret), ['HS256']);
    }
}
