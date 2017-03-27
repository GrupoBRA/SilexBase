<?php

namespace OnyxERP\Core\Application\Service;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-03-25 at 01:25:37.
 */
class JWTServiceTest extends TestCase
{

    /**
     * @var JWTService
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
        $this->object = new JWTService($app);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::encode
     * @todo   Implement testEncode().
     */
    public function testEncode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::decode
     * @todo   Implement testDecode().
     */
    public function testDecode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::push
     * @todo   Implement testPush().
     */
    public function testPush()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::checkJWT
     * @todo   Implement testCheckJWT().
     */
    public function testCheckJWT()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::getJWTPayload
     * @todo   Implement testGetJWTPayload().
     */
    public function testGetJWTPayload()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::getAuthorizationJWT
     * @todo   Implement testGetAuthorizationJWT().
     */
    public function testGetAuthorizationJWT()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::trataJWT
     * @todo   Implement testTrataJWT().
     */
    public function testTrataJWT()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::obj2array
     * @todo   Implement testObj2array().
     */
    public function testObj2array()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers OnyxERP\Core\Application\Service\JWTService::getApp
     * @todo   Implement testGetApp().
     */
    public function testGetApp()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
