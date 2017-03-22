<?php

namespace OnyxERP\Core\Application\Service;

use PHPUnit_Framework_TestCase;
use Silex\Application;

/**
 * Class GuzzleServiceProvider.
 *
 * @author Jean-Philippe Dépigny <jp.depigny@gmail.com>
 * @author jfranciscos4 <silvaivctd@gmail.com>
 */
class GuzzleServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        chdir(__DIR__);
        $this->app = require '../../../../../bootstrap.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testInitialize()
    {
        $guzzle = $this->app['guzzle'];
        $testedObj = new \GuzzleHttp\Client();
        $this->assertInstanceOf(get_class($testedObj), $guzzle);
    }
}
