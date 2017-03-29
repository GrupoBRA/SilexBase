<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use OnyxERP\Core\Application\Service\JwtAPI\Encode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EncodeSpec extends ObjectBehavior
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
        $this->beConstructedWith($app, $dados);
    }
    /**
     * 
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(Encode::class);
    }
    
    function it_should_return_string()
    {
        $this->getResponse()->shouldBeString();
    }
}
