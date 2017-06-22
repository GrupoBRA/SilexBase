<?php

namespace OnyxERP\Core\Application\Service;

use Doctrine\ORM\EntityManager;

/**
 * VersaoRegistroService.
 *
 * PHP version 5.6
 *
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/Core/blob/master/LICENSE (c) 2007/2016, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 2.1.0
 */
class VersaoRegistroService
{

    /**
     * Localiza a versão do registro para um determinado usuário, levando em
     * conta as chaves informadas(ou não) por parâmetro, começando pelo usuarioCod,
     * seguido por orgaoEntidadeCod e finalmente por orgaoCod, caso nenhuma dessas
     * pesquisas encontre um registro, uma última pesquisa é feita na tentativa
     * de localizar o registro global, considerando apenas as restrições informadas
     * no parâmetro $criteria. Para pular qualquer um dos passos, omita alguma das
     * chaves restritoras.
     *
     * @return $entity Entidade informada, com o primeiro registro a satisfazer a busca
     */
    public function getVersaoRegistro($entity, array $criteria, $orgaoCod = null, $orgaoEntidadeCod = null, $usuarioCod = null)
    {
        //se $usuarioCod foi informado, tenta filtrar por usuário.
        if (\is_null($usuarioCod) === false) {
            $userLevelCriteria = $criteria;
            $userLevelCriteria['usuarioCod'] = $usuarioCod;
            $userLevel = $entity->findOneBy($userLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($userLevel)) {
                return $userLevel;
            }
        }

        //se $orgaoEntidadeCod foi informado, tenta filtrar por entidade.
        if (\is_null($orgaoEntidadeCod) === false) {
            $entidadeLevelCriteria = $criteria;
            $entidadeLevelCriteria['orgaoEntidadeCod'] = $orgaoEntidadeCod;
            $entidadeLevel = $entity->findOneBy($entidadeLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($entidadeLevel)) {
                return $entidadeLevel;
            }
        }

        //se $orgaoCod foi informado, tenta filtrar por órgão.
        if (\is_null($orgaoCod) === false) {
            $orgaoLevelCriteria = $criteria;
            $orgaoLevelCriteria['orgaoCod'] = $orgaoCod;
            $orgaoLevel = $entity->findOneBy($orgaoLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($orgaoLevel)) {
                return $orgaoLevel;
            }
        }

        //em último caso, tenta filtrar sem nenhuma das restrições anteriores.
        $appLevel = $entity->findOneBy($criteria, ['dataHora' => "DESC"]);
        if (\is_object($appLevel)) {
            return $appLevel;
        }
    }

    /**
     * Localiza a versão do registro para um determinado usuário, levando em
     * conta as chaves informadas(ou não) por parâmetro, começando pelo usuarioCod,
     * seguido por orgaoEntidadeCod e finalmente por orgaoCod, caso nenhuma dessas
     * pesquisas encontre um registro, uma última pesquisa é feita na tentativa
     * de localizar o registro global, considerando apenas as restrições informadas
     * no parâmetro $criteria. Para pular qualquer um dos passos, omita alguma das
     * chaves restritoras.
     *
     * @return $entity Entidade informada, com o primeiro registro a satisfazer a busca
     */
    public function getVersaoRegistroMultiplo($entity, array $criteria, $orgaoCod = null, $orgaoEntidadeCod = null, $usuarioCod = null)
    {
        //se $usuarioCod foi informado, tenta filtrar por usuário.
        if (\is_null($usuarioCod) === false) {
            $userLevelCriteria = $criteria;
            $userLevelCriteria['usuarioCod'] = $usuarioCod;
            $userLevel = $entity->findBy($userLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($userLevel)) {
                return $userLevel;
            }
        }

        //se $orgaoEntidadeCod foi informado, tenta filtrar por entidade.
        if (\is_null($orgaoEntidadeCod) === false) {
            $entidadeLevelCriteria = $criteria;
            $entidadeLevelCriteria['orgaoEntidadeCod'] = $orgaoEntidadeCod;
            $entidadeLevel = $entity->findOneBy($entidadeLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($entidadeLevel)) {
                return $entidadeLevel;
            }
        }

        //se $orgaoCod foi informado, tenta filtrar por órgão.
        if (\is_null($orgaoCod) === false) {
            $orgaoLevelCriteria = $criteria;
            $orgaoLevelCriteria['orgaoCod'] = $orgaoCod;
            $orgaoLevel = $entity->findBy($orgaoLevelCriteria, ['dataHora' => "DESC"]);
            if (\is_object($orgaoLevel)) {
                return $orgaoLevel;
            }
        }

        //em último caso, tenta filtrar sem nenhuma das restrições anteriores.
        $appLevel = $entity->findBy($criteria, ['dataHora' => "DESC"]);
        if (\is_object($appLevel)) {
            return $appLevel;
        }
    }
    
    /**
     * Destativa versões anterioes de registros de $entityName da pfCod $id
     *
     * @param string        $entityName       Nome da entidade a ser alterada
     * @param integer       $id               pfCod dos registros a serem desativados
     * @param string        $columnStatusName Nome da coluna status da entidade
     * @param EntityManager $em               Objeto do EntityManager, normalmente $app['orm.em']
     *
     * @return bool Em caso de sucesso
     * @throws \Exception Caso o método 'set$columnStatusName()' não existir na
     * entidade em $entityName.
     */
    public function desativaVersaoRegistro($entityName, $id, $columnStatusName, EntityManager $em)
    {
        $entity     = $em->getRepository($entityName);
        $registros  = $entity->findBy(
            [
            'pfCod'             => $id,
            $columnStatusName   => 'A'
            ]
        );

        if (\count($registros) > 0) {
            foreach ($registros as $end) {
                $method = 'set'. \ucfirst($columnStatusName);

                if (\method_exists($end, $method) === false) {
                    throw new \Exception("A coluna '$columnStatusName' não existe ou não está mapeada em $entityName");
                }

                $end->{$method}('I');

                $em->merge($end);
                $em->flush($end);
            }
        }

        return true;
    }

    /**
     * Destativa versões anterioes de registros de $entityName da pfCod $id
     *
     * @param string        $entityName       Nome da entidade a ser alterada
     * @param integer       $id               pfCod dos registros a serem desativados
     * @param string        $columnStatusName Nome da coluna status da entidade
     * @param EntityManager $em               Objeto do EntityManager, normalmente $app['orm.em']
     *
     * @return bool Em caso de sucesso
     * @throws \Exception Caso o método 'set$columnStatusName()' não existir na
     * entidade em $entityName.
     */
    public function desativaVersaoRegistroCriteria($entityName, $criteria, $columnStatusName, EntityManager $em)
    {
        $entity     = $em->getRepository($entityName);
        $registros  = $entity->findBy($criteria);

        if (\count($registros) > 0) {
            foreach ($registros as $end) {
                $method = 'set'. \ucfirst($columnStatusName);

                if (\method_exists($end, $method) === false) {
                    throw new \Exception("A coluna '$columnStatusName' não existe ou não está mapeada em $entityName");
                }

                $end->{$method}('I');

                $em->merge($end);
                $em->flush($end);
            }
        }

        return true;
    }
}
