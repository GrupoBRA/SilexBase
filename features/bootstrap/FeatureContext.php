<?php
//use Behat\MinkExtension\Context\MinkContext;
//use Behat\WebApiExtension\Context\WebApiContext;


use \Behat\Behat\Context\SnippetAcceptingContext;
use \Behat\JwtApiExtension\Context\JwtApiContext;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Psr7\Request;

/**
 * FeatureContext.
 *
 * PHP version 5.6
 *
 * @author    jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 2.2.2
 */
class FeatureContext extends JwtApiContext implements SnippetAcceptingContext
{

    /**
     * @Given I am webApp authenticated
     */
    public function iAmWebAppAuthenticatedApp()
    {
        $url = $this->prepareUrl('http://auth-api.alpha.onyxapis.com/v1/auth/');
        $string = $this->replacePlaceHolder(trim('{"apikey": "NTMwYzIxZTM5YjJi"}'));
        $bodyOption = array(
            'body' => $string,
        );
        $method = 'POST';
        
        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $bodyOption['body']);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url, $bodyOption);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }
        $this->sendRequest();
        $response = json_decode($this->response->getBody()->getContents());
        $this->authorization =  $response->access_token;
        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);
        $this->printResponse();
    }

    /**
     * @Given I am mobileApp authenticated
     */
    public function iAmMobileAppAuthenticatedApp()
    {
        $url = $this->prepareUrl('http://auth-api.alpha.onyxapis.com/v1/auth/');
        $string = $this->replacePlaceHolder(trim('{"apikey": "ZTU3NzE4MTA4ZGQy"}'));
        $bodyOption = array(
            'body' => $string,
        );
        $method = 'POST';
        
        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $bodyOption['body']);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url, $bodyOption);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }
        $this->sendRequest();
        $response = json_decode($this->response->getBody()->getContents());
        $this->authorization =  $response->access_token;
        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);
        $this->printResponse();
    }

    /**
     * @Given I am webUser authenticated
     */
    public function iAmWebUserAuthenticated()
    {
        $this->iAmWebAppAuthenticatedApp();

        $url = $this->prepareUrl('http://account-api.alpha.onyxapis.com/v1/login/');
        $post = '{"email": "teste@braconsultoria.com.br", "password": "betabeta"}';
        $string = $this->replacePlaceHolder(trim($post));
        $bodyOption = array(
            'body' => $string,
        );
        $method = 'POST';
        
        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $bodyOption['body']);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url, $bodyOption);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }
        $this->sendRequest();

        //limpa token anterior sem dados de usuário.
        $this->removeHeader('Authorization');
        
        $response = json_decode($this->response->getBody()->getContents());
        $this->authorization =  $response->access_token;
        //adiciona novo token recebido, agora com dados de usuario.

        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);

        $this->printResponse();
    }
    
    /**
     * @Given I am mobileUser authenticated
     */
    public function iAmMobileUserAuthenticated()
    {
        $this->iAmMobileAppAuthenticatedApp();

        $url = $this->prepareUrl('http://account-api.alpha.onyxapis.com/v1/login/');
        $post = '{"email": "teste@braconsultoria.com.br", "password": "betabeta"}';
        $string = $this->replacePlaceHolder(trim($post));
        $bodyOption = array(
            'body' => $string,
        );
        $method = 'POST';
        
        if (version_compare(ClientInterface::VERSION, '6.0', '>=')) {
            $this->request = new Request($method, $url, $this->headers, $bodyOption['body']);
        } else {
            $this->request = $this->getClient()->createRequest($method, $url, $bodyOption);
            if (!empty($this->headers)) {
                $this->request->addHeaders($this->headers);
            }
        }
        $this->sendRequest();

        //limpa token anterior sem dados de usuário.
        $this->removeHeader('Authorization');
        
        $response = json_decode($this->response->getBody()->getContents());
        $this->authorization =  $response->access_token;
        //adiciona novo token recebido, agora com dados de usuario.

        $this->addHeader('Authorization', 'Bearer ' . $this->authorization);

        $this->printResponse();
    }
}
