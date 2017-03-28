<?php

namespace OnyxERP\Core\Application\Service;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-03-25 at 01:18:30.
 */
class BaseServiceTest extends TestCase
{

    /**
     * @var BaseService
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        chdir(__DIR__);
        $app = require '../../../../../bootstrap.php';
        $this->object = new BaseService($app);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers OnyxERP\Core\Application\Service\BaseService::getPayload
     * @todo   Implement testGetPayload().
     */
    public function testGetPayload()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\BaseService::setPayload
     * @todo   Implement testSetPayload().
     */
    public function testSetPayload()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\BaseService::getJwt
     * @todo   Implement testGetJwt().
     */
    public function testGetJwt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\BaseService::setJwt
     * @todo   Implement testSetJwt().
     */
    public function testSetJwt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
