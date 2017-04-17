<?php

namespace OnyxERP\Core\Application\Service;

use \PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-03-25 at 01:25:37.
 */
class SocialServiceTest extends TestCase
{
    /**
     * @var SocialService
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        \chdir(__DIR__);

        $app = include '../../../../../bootstrap.php';
        $this->object = new SocialService($app);

        if (\defined('URL_SOCIAL_API') === false) {
            \define('URL_SOCIAL_API', 'http://social-api.alpha.onyxapis.com/v1/');
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers OnyxERP\Core\Application\Service\SocialService::buscaNomePessoaFisica
     */
    public function testBuscaNomePessoaFisica()
    {
        $pessoa = $this->object->buscaNomePessoaFisica(1);

        $this->assertTrue(isset($pessoa['pf_cod']));
        $this->assertTrue(isset($pessoa['cpf']));
        $this->assertTrue(isset($pessoa['nome']));
        $this->assertTrue(isset($pessoa['nascimento']));
        $this->assertTrue(isset($pessoa['sexo']));
    }

    /**
     * Devido a necessidade de token para este end-point, ainda não foi possível
     * fazer o teste unitário com uma requisição bem sucedida.
     * @covers OnyxERP\Core\Application\Service\SocialService::searchPessoaFisica
     * @expectedException \Exception
     */
    public function testSearchPessoaFisica()
    {
        $this->object->searchPessoaFisica('02401627146');
    }
}
