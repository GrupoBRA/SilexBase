<?php

namespace OnyxERP\Core\Application\Service\JwtAPI;

use \PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-03-31 at 14:03:05.
 */
class PushTest extends TestCase
{

    /**
     * @var Push
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        chdir(__DIR__);
        $app = include '../../../../../../bootstrap.php';
        
        $dados = [
            "apiKey" => "NTdjOTc0ZjM3YzRmOA==",
            "app" => [
                "id" => "99",
                "apikey" => "57c974f37c4f8",
                "name" => "Dash"
            ]
        ];
        $encode = new Encode($app, $dados);
        $jwt = $encode->getResponse();
        $dados['teste'] = true;
        $this->object = new Push($app, $dados, $jwt);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = null;
    }
    
    /**
     * @covers OnyxERP\Core\Application\Service\JwtAPI\Push::__construct
     */
    public function testPushWithSuccess()
    {
        $response = $this->object->getResponse();
        $this->assertInternalType('string', $response);
    }
    /**
     * @covers OnyxERP\Core\Application\Service\JwtAPI\Push::__construct
     * @expectedException Exception
     */
    public function testPushWithoutSuccess()
    {
        chdir(__DIR__);
        $app = include '../../../../../../bootstrap.php';
        
        $this->object = new Push($app, [], null);
    }

}
