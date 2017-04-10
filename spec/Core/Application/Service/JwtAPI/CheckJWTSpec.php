<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use \OnyxERP\Core\Application\Service\JwtAPI\CheckJWT;
use \OnyxERP\Core\Application\Service\JwtAPI\Encode;
use \PhpSpec\ObjectBehavior;

class CheckJWTSpec extends ObjectBehavior
{

    protected $app;
    protected $jwt;

    /**
     *
     */
    public function let()
    {
        chdir(__DIR__);
        $this->app = require './../../../../../bootstrap.php';
        $dados = [
            "apiKey" => "NTdjOTc0ZjM3YzRmOA==",
            "app" => [
                "id" => "99",
                "apikey" => "57c974f37c4f8",
                "name" => "Dash"
            ]
        ];
        $encode = new Encode($this->app, $dados);
        $this->jwt = $encode->getResponse();
        $this->beConstructedWith($this->app, $this->jwt);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CheckJWT::class);
    }

    public function it_should_return_check_jwt_without_success()
    {
        $this->beConstructedWith($this->app, '');
        $this->shouldThrow('\Exception')->duringGetResponse();
    }

}
