<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \Silex\Application;

/**
 * RecoveryService.
 *
 * PHP version 5.6
 *
 * @author rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.2.0
 */
class RecoveryService
{
    /**
     *
     * @var Application
     */
    private $app;

    /**
     *
     * @var string JWT Token
     */
    private $jwt;

    
    /**
     *
     * @var array
     */
    private $payload;

    /**
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Envia um put ao end-point de definição/recuperação de senha em AccountAPI,
     * para cadastrar uma nova senha informada pelo usuário
     *
     * @param string $cpf CPF do usuário a ser validado
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function recovery($cpf)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions' => false
            ];

            if (!empty($this->getPayload())) {
                $conf['body'] = \json_encode($this->getPayload());
            }

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->put(\URL_ACCOUNT_API . 'change-pass/'. $cpf .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);
                $data = (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                $data = [];
            }

            return [
                'status'    => $response->getStatusCode(),
                'response'  => $data
            ];
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Envia um PUT ao end-point de reenvio do sms de validação em AccountAPI
     *
     * @param string $cpf CPF do usuário a ser validado
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function recoverySms($cpf)
    {
        try {
            $conf = [
                'connect_timeout' => 10,
                'timeout' => 10,
                'exceptions' => false
            ];

            if (!empty($this->getPayload())) {
                $conf['body'] = \json_encode($this->getPayload());
            }

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->put(\URL_ACCOUNT_API . 'signup/recovery-sms/'. $cpf .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);
                $data = (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                $data = [];
            }

            return [
                'status'    => $response->getStatusCode(),
                'response'  => $data
            ];
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Envia uma requisição ao end-point v1/recovery/pass/{login}/ em AccountAPI,
     * para que um sms de recuperação de senha seja enviado ao celular validado
     * pelo usuário.
     *
     * @param string $login CPF ou celular do usuário a ser recuperado
     * @return array Array com a resposta e o status code
     * @throws Exception
     */
    public function recoveryPass($login)
    {
        try {
            $conf = [
                'connect_timeout'   => 10,
                'timeout'           => 10,
                'exceptions'        => false
            ];

            if (!empty($this->getJwt())) {
                $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
            }

            $response = $this->app['guzzle']->get(\URL_ACCOUNT_API . 'recovery/pass/'. $login .'/', $conf);

            $responseText = $response->getBody()->getContents();

            $this->app['monolog']->debug($responseText);

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);
                $data = (isset($responseObj['data']) ? $responseObj['data'] : false);
            } else {
                $data = [];
            }

            return [
                'status'    => $response->getStatusCode(),
                'response'  => $data
            ];
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    public function getJwt()
    {
        return $this->jwt;
    }

    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
    
    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload(array $payload)
    {
        $this->payload = $payload;
        return $this;
    }
}
