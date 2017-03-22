<?php
namespace OnyxERP\Core\Application\Service;

use DateTime;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use function file_get_contents;
use function file_put_contents;

/**
 * JSONServiceProvider.
 *
 * Cria arquivo JSON
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.6.3
 */
class JSONServiceProvider implements ServiceProviderInterface
{

    public function __construct()
    {}

    /**
     *
     * {@inheritdoc}
     *
     */
    public function boot(Container $app)
    {}

    /**
     *
     * {@inheritdoc}
     *
     */
    public function register(Container $app)
    {
        $app['json'] = function ($app) {
            return new self();
        };
    }

    /**
     *
     * @param array $parameters
     * @param string $filename
     * @throws \InvalidArgumentException
     */
    public function createJSON(array $parameters, $filename)
    {
        if (empty($filename)) {
            throw new \InvalidArgumentException('Filename not found');
        }
        \file_put_contents($filename, \json_encode($parameters));
    }

    /**
     *
     * @param string $filename
     * @return array
     * @throws \InvalidArgumentException
     */
    public function readJSONByArray($filename)
    {
        if (! \file_exists($filename)) {
            throw new \InvalidArgumentException('Filename not found');
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
