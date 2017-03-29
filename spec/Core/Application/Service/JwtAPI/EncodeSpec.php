<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use OnyxERP\Core\Application\Service\JwtAPI\Encode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EncodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Encode::class);
    }
}
