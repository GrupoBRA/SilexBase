<?php
namespace OnyxERP\Core\Application\Service;

use \Silex\Application;

/**
 * BaseService.
 *
 * PHP version 7.0+
 *
 * @author    rinzler <github.com/feliphebueno>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 * @license   https://github.com/BRAConsultoria/SilexBase/blob/master/LICENSE (c) 2007/2017,
 *          Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */
class FlushService
{

    /**
     *
     * @var Application
     */
    protected $app;
    
    /**
     *
     * @var string Path da pasta universal do cache
     */
    private $cachePath = \CACHE_PATH;

    /**
     *
     * @var string
     */
    private $api;
    
    /**
     *
     * @var array Paths a serem buscados
     */
    private $paths = [];
    
    /**
     *
     * @var array Ids dos arquivos a serem removidos
     */
    private $ids = [];

    /**
     * Constructor
     *
     * @param Application $app Silex App
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Remove os arquivos que forem encontrados nos paths informados, com as
     * respectivas ids.
     * 
     * @return array
     * @throws Exception Se não tiver permissão de escrita em algum arquivo 
     */
    public function flush()
    {
        $removidos = [];
        $total = 0;
        
        foreach ($this->paths as $path) {
            foreach($this->ids as $filename){
                $filePath = $this->cachePath .'/'. $this->api .'/json/'. $path .'/'. $filename;
                if(\is_file($filePath)){
                    \unlink($filePath);
                    \array_push($removidos, $filePath);
                    $total++;
                }
            }
        }

        return [
            'total' => $total,
            'arquivos' => $removidos
        ];
    }
    /**
     * Remove os arquivos que forem encontrados nos paths informados, com as
     * respectivas ids.
     * 
     * @return type
     * @throws Exception Se não tiver permissão de escrita em algum arquivo
     */
    public function flushAbsolutePath()
    {
        $removidos = [];
        $total = 0;

        foreach ($this->paths as $path) {
            foreach($this->ids as $id){
                $filePath = $this->cachePath .'/'. $this->api .'/'. $path .'/'. $id;
                if(\is_file($filePath)){
                    \unlink($filePath);
                    \array_push($removidos, $filePath);
                    $total++;
                }
            }
        }

        return [
            'total' => $total,
            'arquivos' => $removidos
        ];
    }

    /**
     * Adiciona uma id à lista de ids a serem buscados para remoção de cache
     * 
     * @param string $id
     * @return $this
     */
    public function appendId(string $id)
    {
        \array_push($this->ids, $id);
        return $this;
    }
    
    /**
     * Adiciona um path à lista de paths a serem buscados para remoção de cache
     * 
     * @param string $path
     * @return $this
     */
    public function appendPath(string $path)
    {
        \array_push($this->paths, $path);
        return $this;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setApp(Application $app) 
    {
        $this->app = $app;
        return $this;
    }
    
    public function getCachePath() 
    {
        return $this->cachePath;
    }

    public function getApi() 
    {
        return $this->api;
    }

    public function setCachePath(string $cachePath) 
    {
        $this->cachePath = $cachePath;
        return $this;
    }

    public function setApi(string $api) 
    {
        $this->api = $api;
        return $this;
    }
}
