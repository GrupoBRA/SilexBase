<?php

namespace spec\OnyxERP\Core\Application\Service;

use OnyxERP\Core\Application\Service\JWTService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JWTServiceSpec extends ObjectBehavior
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
    
    public function it_is_initializable()
    {
        $this->shouldHaveType(JWTService::class);
    }
}
