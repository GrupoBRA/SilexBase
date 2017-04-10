<?php

namespace spec\OnyxERP\Core\Application\Service\JwtAPI;

use \OnyxERP\Core\Application\Service\JwtAPI\Decode;
use \OnyxERP\Core\Application\Service\JwtAPI\Encode;
use \PhpSpec\ObjectBehavior;

class DecodeSpec extends ObjectBehavior
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
        $this->beConstructedWith($this->app);
    }
    /**
     * 
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType(Decode::class);
    }
    
}
