<?php

namespace spec\OnyxERP\Core\Application\Service;

use OnyxERP\Core\Application\Service\AboutService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AboutServiceSpec extends ObjectBehavior
{
    /**
     *
     */
    public function let()
    {
        chdir(__DIR__);
        $app = require './../../../../bootstrap.php';
        $this->beConstructedWith($app);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(AboutService::class);
    }
    
//    function it_is_get_app_id()
//    {
//        $payload = ['teste'=>''];
//        $key = '1023456';
//        
//        $jwt = \Firebase\JWT\JWT::encode($payload, $key);
//            
//        $this->setJwt($jwt)->setPayload($payload);
//        $this->getDadosApp()->shoulBeArray();
//    }
}
