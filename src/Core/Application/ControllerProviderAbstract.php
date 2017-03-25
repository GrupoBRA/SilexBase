<?php

namespace OnyxERP\Core\Application;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ControllerProviderAbstract.
 *
 * Controller Provider Abstract
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/SilexBase/blob/master/LICENSE Proprietary
 * @version 1.0.2
 */
abstract class ControllerProviderAbstract
{

     const HTTP_CODE_SUCCESS = 200;
     const HTTP_CODE_ERROR = 400;

    /**
     * [$app description].
     *
     * @var \Silex\Application
     */
    private $app;

     protected function jsonResponseDecorator(JsonResponse $jsonResponse, array $result, $statusError = self::HTTP_CODE_ERROR, $statusSuccess = self::HTTP_CODE_SUCCESS)
     {
	 $data = [
             'status' => false,
	     'data' => null
         ];

	 $jsonResponse->setData($data);
	 
     }

    /**
     * Response JSON.
     *
     * Return an response of type JSON
     *
     * {@inheritdoc}
     *
     * @param array $resultados
     * @param int $statusErro
     * @param int $statusSucceso
     *
     * @return JsonResponse
     */
    protected function responseJson(array $resultados, $statusErro = 400, $statusSucceso = 200)
    {
        $response = new JsonResponse();
        $response->setData(array(
            'status' => false,
            'data' => $resultados
        ));
        $response->setStatusCode($statusErro);
        if (count($resultados) > 0 && !isset($resultados['error'])) {
            $response->setEncodingOptions(JSON_NUMERIC_CHECK);
            $response->setData(array(
                'status' => true,
                'data' => $resultados
            ));
            $response->setStatusCode($statusSucceso);
        }
        $response->headers->set('Content-Type', 'UTF-8');
        $response->headers->set('Accept-Encoding', 'GZIP');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Gets the [$app description].
     *
     * @return \Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Sets the [$app description].
     *
     * @param \Silex\Application $app
     *            the app
     *
     * @return self
     */
    public function setApp(\Silex\Application $app)
    {
        $this->app = $app;

        return $this;
    }
}
