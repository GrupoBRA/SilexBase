<?php

namespace spec\OnyxERP\Core\Application\Service;

use \OnyxERP\Core\Application\Service\BiometriaService;
use \OnyxERP\Core\Domain\ValueObject\PfCod;
use \PhpSpec\ObjectBehavior;

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
    
    function it_get_dados_biometria_com_sucesso()
    {
        $pfCod = new PfCod(1);
        $this->getDadosBiometria($pfCod)->shouldBeArray();
    }
}
