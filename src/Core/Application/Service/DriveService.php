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
    
    /**
     * Método para upload de arquivos para DriveAPI
     * 
     * @param int $refCod
     * @param int $appModCod
     * @return array|boolean 
     * @throws Exception
     */
    public function upload($refCod, $appModCod = 7)
    {
        try {

            $url = URL_DRIVE_API . $refCod .'/modulo/'. $appModCod .'/';

            $confs = [
                'timeout'       => '20',
                'exceptions'    => false,
                'headers'       => [
                    'Authorization' => "Bearer ". $this->getJwt()
                ],
                'body' => \json_encode($this->getPayload())
            ];

            $response = $this->guzzle->post($url, $confs);

            $responseText = $response->getBody()->getContents();

            if ($response->getStatusCode() === 200) {
                $responseObj = \json_decode($responseText, true);

                return $responseObj['data'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception('Não foi possível fazer o upload para DriveAPI!!');
        }
    }
}
