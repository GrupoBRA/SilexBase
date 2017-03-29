<?php

namespace spec\OnyxERP\Core\Application\Service;

use \OnyxERP\Core\Application\Service\DriveService;
use \OnyxERP\Core\Domain\ValueObject\PfCod;
use \PhpSpec\ObjectBehavior;

class DriveServiceSpec extends ObjectBehavior
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
    /**
     * 
     */
    function it_is_initializable()
    {
        $this->shouldHaveType(DriveService::class);
    }
    /**
     * 
     */
    function it_get_dados_digitalizacao_com_sucesso()
    {
        $pfCod = new PfCod(1);
        $lista = $this->getDadosDigitalizacao($pfCod);
        $lista->shouldBeArray();
        
    }
}
