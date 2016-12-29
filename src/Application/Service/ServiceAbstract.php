<?php

namespace OnyxERP\Core\Application\Service;

use \Silex\Application;

/**
 * ServiceAbstract.
 *
 * Service Abstract
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version   1.0.0
 */
class ServiceAbstract
{
    /**
     *
     * @var Application
     */
    private $app;
    /**
     *
     * @var integer
     */
    private $usuarioCod;
    /**
     *
     * @param Application $app
     * @param integer $usuarioCod
     */
    public function __construct(Application $app, $usuarioCod = 0)
    {
        $this->app = $app;
        $this->usuarioCod = $usuarioCod;
    }
    /**
     *
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }
    /**
     *
     * @return integer
     */
    public function getUsuarioCod()
    {
        return $this->usuarioCod;
    }
}
