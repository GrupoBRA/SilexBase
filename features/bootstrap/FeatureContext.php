<?php

use \Behat\Behat\Context\ClosuredContextInterface,
    \Behat\Behat\Context\TranslatedContextInterface,
    \Behat\Behat\Context\BehatContext,
    \Behat\Behat\Exception\PendingException;
use \Behat\Gherkin\Node\PyStringNode,
    \Behat\Gherkin\Node\TableNode;
use \Behat\Behat\Context\SnippetAcceptingContext;
// use Behat\MinkExtension\Context\MinkContext;
// use Behat\WebApiExtension\Context\WebApiContext;
use \Behat\JwtApiExtension\Context\JwtApiContext;

/**
 * FeatureContext.
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
class FeatureContext extends JwtApiContext implements SnippetAcceptingContext
{

    /**
     * @Given I am app authenticated
     */
    public function iAmAppAuthenticatedApp()
    {
        $url = $this->prepareUrl($this->config['base_url'] . '/auth/');
        $string = $this->replacePlaceHolder(trim('{"apikey": "NTdjOTc0ZjM3YzRmOA=="}'));
        $this->request = $this->getClient()->createRequest(
            'POST', $url, array(
                'headers' => $this->getHeaders(),
                'body' => $string,
            )
        );
        $this->sendRequest();
        $response = $this->response->json();
        $this->authorization = $response['access_token'];
        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);
        $this->printResponse();
    }

    /**
     * @Given I am user authenticated
     */
    public function iAmUserAuthenticated()
    {
        $this->iAmAppAuthenticatedApp();

        $url = $this->prepareUrl($this->config['base_url'] . '/login/');
        $post = '{"email": "teste@braconsultoria.com.br", "password": "betabeta"}';
        $string = $this->replacePlaceHolder(trim($post));
        $this->request = $this->getClient()->createRequest(
            'POST', $url, array(
                'headers' => $this->getHeaders(),
                'body' => $string,
            )
        );
        $this->sendRequest();
        $this->printResponse();
    }

    /**
     * @Given I am new app authenticating
     */
    public function iAmNewAppAuthenticating()
    {
        $this->iAmAppAuthenticatedApp();
        $this->removeHeader('Authorization');

        $response = $this->response->json();
        $this->authorization = $response['access_token'];
        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);

        $url = $this->prepareUrl('http://api.auth.alpha.onyxapp.com.br/v1/auth/');
        $string = $this->replacePlaceHolder(trim('{"apikey": "ZmEwOWVmNTc4OTY0","k1":"LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlHZk1BMEdDU3FHU0liM0RRRUJBUVVBQTRHTkFEQ0JpUUtCZ1FDc2ErYmR5VzAzUEdoOTNaQzFoaDJVL2tJSwpJM21mUGt6WEFnU09RWEZlT1JrUEY2TGZxRU9vSEFleU43UXIxZGxiZ1ZUT2RYczRvZEFlNHJlKytzelhFNTMyCkNWRmNkRC81TTFDMGQ1Um1VOGZpdzkyRXVpTVpEa2EzU0xGeVEwNnhJR2VVcGlCYklOamtyeVVCWmFWS2Zyb0cKVjZOU2dqNWNtbGpGVDJEbitRSURBUUFCCi0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQ=="}'));
        $this->request = $this->getClient()->createRequest('POST', $url, array(
            'headers' => $this->getHeaders(),
            'body' => $string
        ));

        $this->sendRequest();
        $this->printResponse();
    }
}
