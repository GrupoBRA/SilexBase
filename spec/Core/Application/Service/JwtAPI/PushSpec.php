<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use \OnyxERP\Core\Application\Service\JwtAPI\Encode;
use \OnyxERP\Core\Application\Service\JwtAPI\Push;
use \PhpSpec\ObjectBehavior;

class PushSpec extends ObjectBehavior
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
        $jwt = (new Encode($app, $dados))->getResponse();
        $this->beConstructedWith($app, $dados, $jwt);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(Push::class);
    }
}
