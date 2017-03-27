<?php
namespace OnyxERP\Core\Application\Service;

use Exception;
use Silex\Application;
use URL_BIOMETRIA_API;

/**
 * BiometraService.
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE Proprietary
 *
 * @version 1.1.0
 */
class BiometriaService extends BaseService
{

    /**
     *
     * @return array
     * @throws Exception Em caso de erro não tratável
     */
    public function getDadosBiometria($pfCod)
    {
        $conf = [
            'body' => $this->getPayload(),
            'exceptions' => false
        ];

        if (! empty($this->getJwt())) {
            $conf['headers'] = [
                'Authorization' => 'Bearer ' . $this->getJwt()
            ];
        }

        $response = $this->app['guzzle']->get(URL_BIOMETRIA_API . 'full/' . $pfCod . '/', $conf);

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
