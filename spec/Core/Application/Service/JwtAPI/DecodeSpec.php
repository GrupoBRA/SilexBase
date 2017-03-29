<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use OnyxERP\Core\Application\Service\JwtAPI\Decode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecodeSpec extends ObjectBehavior
{
    /**
     *
     */
    public function let()
    {
        chdir(__DIR__);
        $app = require './../../../../../bootstrap.php';
        $dados = [
            "apiKey" => "NTdjOTc0ZjM3YzRmOA==",
            "app" => [
                "id" => "99",
                "apikey" => "57c974f37c4f8",
                "name" => "Dash"
            ]
        ];
        $this->beConstructedWith($app);
    }
    /**
     * 
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType(Decode::class);
    }
}
