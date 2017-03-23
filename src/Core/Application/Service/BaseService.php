<?php

namespace OnyxERP\Core\Application\Service;

use \Silex\Application;

/**
 * BaseService.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
class BaseService
{
    /**
 * @var string Request body
*/
    private $payload;

    /**
 * @var string Json Web Token
*/
    private $jwt;
    
    /**
     *
     * @var Application
     */
    protected $app;

    /**
     * [__construct description].
     *
     * @param Application $app [description]
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     *
     * @return type
     */
    public function getPayload()
    {
        return $this->payload;
    }
    /**
     *
     * @param type $payload
     * @return BaseService
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
    /**
     *
     * @return type
     */
    public function getJwt()
    {
        return $this->jwt;
    }
    /**
     *
     * @param type $jwt
     * @return BaseService
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }
}
