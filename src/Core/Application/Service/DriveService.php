<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \OnyxERP\Core\Domain\ValueObject\PfCod;
use const \URL_DRIVE_API;

/**
 * DriveService.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.1.0
 */
class DriveService extends BaseService
{
   
    /**
     *
     */
    const MODULO_7 = 7;
    /**
     * @return array
     * @throws Exception Em caso de erro não tratável
     */
    public function getDadosDigitalizacao(PfCod $pfCod)
    {
        $conf = [
                'body' => $this->getPayload(),
                'exceptions' => false
            ];

        if (!empty($this->getJwt())) {
            $conf['headers'] = [
                    'Authorization' => 'Bearer ' . $this->getJwt(),
                ];
        }
        try {
            $response = $this->app['guzzle']->get(URL_DRIVE_API . $pfCod->getValue() . '/modulo/' . self::MODULO_7 . '/', $conf);
            $decoded = \json_decode($response->getBody()->getContents(), true);
        } catch (Exception $ex) {
            $decoded = [];
        }

        if (isset($decoded['data']) === true) {
            return $decoded['data'];
        }
            
        return [];
    }
}
