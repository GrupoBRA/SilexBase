<?php

namespace spec\OnyxERP\Core\Domain\ValueObject;

use OnyxERP\Core\Domain\ValueObject\PfCod;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * PfCodSpec.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.0.4
 */
class PfCodSpec extends ObjectBehavior
{
    /**
     *
     */
    public function let()
    {
        $this->beConstructedWith(1);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(PfCod::class);
    }
    
    function it_invalid_argument()
    {
        $this->beConstructedWith('null');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
    
    function it_get_value()
    {
        $this->getValue()->shouldBeInteger();
    }
}
