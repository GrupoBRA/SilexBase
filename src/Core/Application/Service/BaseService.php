<?php
namespace OnyxERP\Core\Application\Service;

use \Silex\Application;

/**
 * BaseService.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/SilexBase/blob/master/LICENSE (c) 2007/2017,
 *          Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
class BaseService
{

    /**
     *
     * @var string Request body
     */
    private $payload;

    /**
     *
     * @var string Json Web Token
     */
    private $jwt;

    /**
     *
     * @var Application
     */
    protected $app;
    /**
     *
     * @var mixed $response
     */
    protected $response;

    /**
     * Constructor of BaseService
     *
     * @param Application $app
     *            Silex App
     * @param string      $jwt
     *            Json Web Token
     * @param string      $payload
     *            Request body
     */
    public function __construct(Application $app, $jwt = null, $payload = null)
    {
        $this->app = $app;

        if (! empty($jwt)) {
            $this->setJwt($jwt);
        }

        if (! empty($payload)) {
            $this->setPayload($payload);
        }
    }

    /**
     * Get Request Body of Request
     *
     * @return type
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set Request Body of Request
     *
     * @param  string $payload
     *            Request Body
     * @return BaseService
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Get JSON Web Token of Request
     *
     * @return string
     */
    public function getJwt()
    {
        return $this->jwt;
    }

    /**
     * Set JSON Web Token of Request
     *
     * @param  type $jwt
     * @return BaseService
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
    /**
     *
     * @param mixed $response
     * @return \OnyxERP\Core\Application\Service\BaseService
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }
            
    
    /**
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setApp(Application $app) 
    {
        $this->app = $app;
        return $this;
    }
}
