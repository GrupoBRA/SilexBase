<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use OnyxERP\Core\Application\Service\JwtAPI\Push;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PushSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Push::class);
    }
}
