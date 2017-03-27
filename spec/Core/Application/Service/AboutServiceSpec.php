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
}
