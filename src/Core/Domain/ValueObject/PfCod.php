<?php

namespace OnyxERP\Core\Domain\ValueObject;

/**
 * PfCod.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.0.4
 */
class PfCod
{
    /**
     *
     * @var integer 
     */
    private $pfCod;
    /**
     * 
     * @param integer $pfCod
     * @throws InvalidArgumentException
     */
    public function __construct($pfCod)
    {
        if (!filter_var($pfCod, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a integer', $pfCod));
        }
        
        $this->pfCod = $pfCod;
    }
    /**
     * 
     * @return type
     */
    public function getValue()
    {
        return $this->pfCod;
    }
    /**
     * 
     * @return type
     */
    public function __toString()
    {
        return $this->pfCod;
    }
    /**
     * 
     * @param PfCod $pfCod
     * @return boolean
     */
    public function equals(PfCod $pfCod)
    {
        return $this->pfCod === $pfCod->getValue();
    }
}
