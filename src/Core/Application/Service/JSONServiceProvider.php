<?php
namespace OnyxERP\Core\Application\Service;

use \InvalidArgumentException;
use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

/**
 * JSONServiceProvider.
 *
 * Create file of type JSON
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/SilexBase/blob/master/LICENSE Proprietary
 *
 * @version 1.0.2
 */
class JSONServiceProvider implements ServiceProviderInterface
{
    /**
     *
     */
    const NAME_SERVICE_PROVIDER_REGISTER = 'json';

    private function createDirectory($directory)
    {
        if (file_exists($directory) === false) {
	    mkdir($directory, 0700, true);
	}
    }

    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Container $app)
    {
    }

    /**
     * {@inheritdoc}
     * @throws DomainException
     */
    public function register(Container $app)
    {
	if (isset($app[self::NAME_SERVICE_PROVIDER_REGISTER])) {
	    throw new DomainException('Position exist in App (Silex\Application)');
	}

        $app[self::NAME_SERVICE_PROVIDER_REGISTER] = function ($app) {
            return new self();
        };
    }
    
    /**
     *
     * @param array  $parameters
     * @param string $filename
     * @throws InvalidArgumentException
     */
    public function createJSON(array $parameters, $filename)
    {
        if (empty($filename)) {
            throw new InvalidArgumentException('Filename not found');
        }
        \file_put_contents($filename, \json_encode($parameters));
    }

    /**
     *
     * @param string $filename
     * @return array
     * @throws InvalidArgumentException
     */
    public function readJsonToArray($filename)
    {
        if (! \file_exists($filename)) {
            throw new InvalidArgumentException('Filename not found');
        }
        $file = \file_get_contents($filename);
        return \json_decode($file, true);
    }

    /**
     *
     * @param integer $filenameTime
     * @param integer $delay
     * @return boolean
     */
    public function checkDelayByBuildJSON($filenameTime, $delay)
    {
        $dateTimeFilename = new \DateTime();
        $dataTimeNow = clone $dateTimeFilename;
        $dateTimeFilename->setTimestamp($filenameTime);
        $diff = $dateTimeFilename->diff($dataTimeNow);

        if ($diff->i >= $delay) {
            return true;
        }
        return false;
    }
}
