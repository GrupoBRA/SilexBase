<?php

namespace OnyxERP\Core\Application\Service;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OnyxERP\Core\Application\RSA\RSA;
use phpseclib\Crypt\AES;

/**
 * FormCryptService.
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version   2.2.2
 */
class FormCryptService extends ServiceAbstract
{
    /** @var Symfony\Component\HttpFoundation\Request Objeto request */
    private $request;

    /** @var array Payload data do token */
    private $tokenPayload;

    /** @var bool Ativa desativa criptografia de dados */
    private $intercepting;

    /** @var SecurityService */
    private $securityService;

    /** @var RSA Objeto da classe RSA com a chave privada do server */
    private $rsaServer;

    /** @var RSA Objeto da classe RSA com a chave privada do server */
    private $rsaClient;

    /** @var AES Objeto da classe AES */
    private $aes;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->securityService = new SecurityService($app);
        $this->rsaServer = new RSA();
        $this->rsaClient = new RSA();

        //MODE OF OPERATION 4 = OFB - Output Feedback
        $this->aes = new AES(4);
    }
    /**
     * [config description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function config(Request $request)
    {
        $this->setRequest($request);
        $headers = $request->server->getHeaders();

        if (isset($headers['AUTHORIZATION']) and !empty($headers['AUTHORIZATION'])) {
            $jwtService = new JWTService($this->getApp());
            $tokenPayload = $jwtService->getJWTPayload($jwtService->getAuthorizationJWT($request));

            if (isset($tokenPayload['data'])) {
                $this->setTokenPayload($tokenPayload['data']);
            }

            $this->setIntercepting($tokenPayload['data']);
        }

        return $this;
    }
    /**
     * [interceptRequest description].
     *
     * @return [type] [description]
     */
    public function interceptRequest()
    {
        $request = $this->getRequest();
        if ($this->getIntercepting() === true and \preg_match('/[auth\/]{5}/', $request->getPathInfo()) != true) {
            $plainData = $this->decriptRequestData($request->getContent());

            return $this->overrideRequestBody($request, $plainData);
        } else {
            return $this->getRequest();
        }
    }
    /**
     * [interceptResponse description].
     *
     * @param Response $response [description]
     *
     * @return [type] [description]
     */
    public function interceptResponse(Response $response)
    {
        $request = $this->getRequest();
        if ($this->getIntercepting() === true and \preg_match('/[auth\/]{5}/', $request->getPathInfo()) != true) {
            $cipherData = $this->encriptResponseData($response->getContent());

            return $this->overrideResponseBody($response, $cipherData);
        } else {
            return $response;
        }
    }
    /**
     * [overrideRequestBody description].
     *
     * @param Request $request [description]
     * @param [type]  $body    [description]
     *
     * @return [type] [description]
     */
    private function overrideRequestBody(Request $request, $body)
    {
        $request->initialize(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all(),
                $body
        );

        return $request;
    }
    /**
     * [overrideResponseBody description].
     *
     * @param Response $response [description]
     * @param [type]   $content  [description]
     *
     * @return [type] [description]
     */
    private function overrideResponseBody(Response $response, $content)
    {
        $response->setContent($content);

        return $response;
    }
    /**
     * [decriptRequestData description].
     *
     * @param [type] $cipherData [description]
     *
     * @return [type] [description]
     */
    private function decriptRequestData($cipherData)
    {
        $cryptData = $this->tokenPayload['app']['crypt'];
        $aesKeyData = $this->getAESKeyData($cryptData);
        $cryptedBytes = $this->securityService->strBytes2Bin(\base64_decode($cipherData));

        if (empty($aesKeyData[0]) or empty($aesKeyData[1])) {
            throw new \Exception('Falha ao recuperar a chave simétrica!');
        }

        $this->aes->setKey($aesKeyData[0]);
        $this->aes->setIV($aesKeyData[1]);

        return $this->aes->decrypt($cryptedBytes);
    }
    /**
     * [encriptResponseData description].
     *
     * @param [type] $plainData [description]
     *
     * @return [type] [description]
     */
    private function encriptResponseData($plainData)
    {
        $cryptData = $this->tokenPayload['app']['crypt'];
        $aesKeyData = $this->getAESKeyData($cryptData);

        if (empty($aesKeyData[0]) or empty($aesKeyData[1])) {
            throw new \Exception('Falha ao recuperar a chave simétrica!');
        }

        $this->aes->setKey($aesKeyData[0]);
        $this->aes->setIV($aesKeyData[1]);

        $cryptedBytes = $this->aes->encrypt($plainData);

        return \base64_encode($this->securityService->bin2strBytes($cryptedBytes));
    }
    /**
     * [getAESKeyData description].
     *
     * @param [type] $cryptData [description]
     *
     * @return [type] [description]
     */
    private function getAESKeyData($cryptData)
    {
        $serverPrivateKey = $this->securityService->getServerRSAKeys($cryptData['path']);
        $this->rsaServer->setPrivateKey($serverPrivateKey['private']);

        return [
            $this->rsaServer->decrypt(\base64_decode($cryptData['kss'][0])),
            $this->rsaServer->decrypt(\base64_decode($cryptData['kss'][1])),
        ];
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        return $this->request;
    }
    /**
     * [setRequest description].
     *
     * @param Request $request [description]
     */
    private function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
    /**
     * [getTokenPayload description].
     *
     * @return [type] [description]
     */
    private function getTokenPayload()
    {
        return $this->tokenPayload;
    }
    /**
     * [setTokenPayload description].
     *
     * @param array $tokenPayload [description]
     */
    private function setTokenPayload(array $tokenPayload)
    {
        $this->tokenPayload = $tokenPayload;

        return $this;
    }
    /**
     * [getIntercepting description].
     *
     * @return [type] [description]
     */
    public function getIntercepting()
    {
        return $this->intercepting;
    }
    /**
     * [setIntercepting description].
     *
     * @param array $tokenPayload [description]
     */
    public function setIntercepting(array $tokenPayload)
    {
        $this->intercepting = ((isset($tokenPayload['app']['security']) and $tokenPayload['app']['security'] === 'A') ? true : false);

        return $this;
    }
}
