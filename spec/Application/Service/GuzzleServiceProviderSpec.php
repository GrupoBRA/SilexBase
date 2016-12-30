<?php

namespace spec\OnyxERP\Core\Application\Service;

use OnyxERP\Core\Application\Service\GuzzleServiceProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuzzleServiceProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(GuzzleServiceProvider::class);
    }
}
