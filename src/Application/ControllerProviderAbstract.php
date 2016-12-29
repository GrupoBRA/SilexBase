<?php
namespace OnyxERP\Core\Application;

use InvalidArgumentException;
use OnyxERP\Core\Application\Service\AuthService;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * ControllerProviderAbstract.
 *
 * Controller Provider Abstract
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.6.2
 */
abstract class ControllerProviderAbstract
{

    /**
     * [$app description].
     *
     * @var \Silex\Application
     */
    private $app;

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

        $response->setStatusCode($statusErro);
        if (count($resultados) > 0 && ! isset($resultados['error'])) {
            $response->setEncodingOptions(JSON_NUMERIC_CHECK);
            $response->setData(array(
                'status' => true,
                'data' => $resultados
            ));
            $response->setStatusCode($statusSucceso);
        } else {
            $response->setData(array(
                'status' => false,
                'data' => $resultados
            ));
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
    private function setApp(\Silex\Application $app)
    {
        $this->app = $app;

        return $this;
    }
}
