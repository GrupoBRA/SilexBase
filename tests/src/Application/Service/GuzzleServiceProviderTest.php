<?php

namespace OnyxERP\Core\Application\Service;

use Silex\Application;

/**
 * Class GuzzleServiceProvider.
 *
 * @author Jean-Philippe Dépigny <jp.depigny@gmail.com>
 * @author jfranciscos4 <silvaivctd@gmail.com>
 */
class GuzzleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LoginService
     */
    protected $object;

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
        $this->app = require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'bootstrap.php';
        $this->object = (new GuzzleServiceProvider())->register($this->app);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \OnyxERP\Core\Application\Service\GuzzleServiceProvider::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf('Silex\Application', $this->app);
        $this->object = new GuzzleServiceProvider();
    }
}
