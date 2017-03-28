<?php

namespace spec\OnyxERP\Core\Application\Service;

use OnyxERP\Core\Application\Service\BiometriaService;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BiometriaServiceSpec extends ObjectBehavior
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
        $this->shouldHaveType(BiometriaService::class);
    }
}
