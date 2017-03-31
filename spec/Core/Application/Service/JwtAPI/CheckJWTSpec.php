<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use OnyxERP\Core\Application\Service\JwtAPI\CheckJWT;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CheckJWTSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CheckJWT::class);
    }
}
