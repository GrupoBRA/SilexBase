<?php

namespace spec\OnyxERP\Core\Application\Service;

use OnyxERP\Core\Application\Service\DriveService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DriveServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DriveService::class);
    }
}
