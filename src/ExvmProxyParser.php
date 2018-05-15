<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:25
 */

namespace ExvmProxyParser;

use ExvmProxyParser\Service\Service;

class ExvmProxyParser
{
    private $list = [];
    private $lastService;
    private $servicesConfig = [];
    private $disabledServices = [];

    public function getList(){
        return $this->list;
    }

    public function startParsing($limit = 1000, $selfRun = false){
        if(count($this->list) >= $limit){
            return;
        }

        if(!$selfRun){
            $this->lastService = null;
            $this->list = [];
        }

        if($this->executeService()){
            $this->startParsing($limit, true);
        }
    }

    public function disableService($service){
        if(in_array($service, $this->disabledServices)){
            return;
        }

        $this->disabledServices[] = $service;
    }

    public function enableService($service){
        if($key = array_search($service, $this->disabledServices) !== false){
            unset($this->disabledServices[$key]);
        }
    }

    public function disableAllServices(){
        $this->disabledServices = $this->getServices();
    }

    public function enableAlLServices(){
        $this->disabledServices = [];
    }

    public function setConfig($service, $config = []){
        $this->servicesConfig[$service] = $config;
    }

    private function executeService(){
        $services = $this->getServices();

        $enabledServices = [];

        foreach ($services as $service) {
            if(in_array($service, $this->disabledServices) === false){
                $enabledServices[] = $service;
            }
        }

        if(empty($enabledServices)){
            return false;
        }

        if($this->lastService == $enabledServices[count($enabledServices) - 1]){
            return false;
        }

        $lastServiceKey = 0;

        foreach ($enabledServices as $key => $service) {
            if($service == $this->lastService){
                $lastServiceKey = $key;
            }
        }

        if(!empty($this->lastService)){
            $key = $lastServiceKey + 1;
        }else{
            $key = 0;
        }

        $service = "ExvmProxyParser\\Service\\" . $enabledServices[$key];
        $config = !empty($this->servicesConfig[$enabledServices[$key]]) ? $this->servicesConfig[$enabledServices[$key]] : [];
        $service = new $service($config);
        $service->startParse();
        $this->list = array_merge($this->list, $service->getList());

        return true;
    }

    private function getServices(){
        $files = scandir(__DIR__ . "/service");

        $services = [];

        foreach ($files as $file) {
            if(strpos($file, "Service") <= 0){
                continue;
            }

            $services[] = explode(".php", $file)[0];
        }

        return $services;
    }

}