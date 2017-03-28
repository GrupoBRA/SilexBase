<?php

namespace OnyxERP\Core\Application\Service;

use \Exception;
use \OnyxERP\Core\Domain\ValueObject\PfCod;
use const \URL_DRIVE_API;

class DriveService extends BaseService
{    
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

        $response = $this->app['guzzle']->get(URL_DRIVE_API . $pfCod->getValue() . '/modulo/7/', $conf);

        try {
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