<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:26
 */

namespace ExvmProxyParser\Service;


use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

class Service
{
    protected $url = null;
    protected $list = [];
    protected $currentPage = 1;
    protected $proxyLimit = 100;

    public function startParse($nextPage = null, $prepareDom = true){
        if(!empty($nextPage)){
            $this->url = $nextPage;
        }

        $response = $this->sendRequest();

        if($prepareDom){
            $dom = $this->htmlToDomObject($response);
        }else{
            $dom = $response;
        }

        $proxies = $this->findProxiesInDom($dom);
        $this->list = array_merge($this->list, $proxies);

        if($this->proxyLimit > count($this->list)){
            $nextPage = $this->findNextPage();

            if(!empty($nextPage)){
                $this->currentPage ++;
                $this->startParse($nextPage);
            }
        }
    }

    protected function findNextPage(){
        return null;
    }

    protected function htmlToDomObject($html){
        $dom = new Dom();

        return $dom->load($html, [
            "removeScripts" => false
        ]);
    }

    protected function findProxiesInDom($dom){
        return [];
    }

    public function getList(){
        $list = [];

        foreach ($this->list as $item) {
            $list[] = $item["protocol"] . "://" . $item["ip"] . ":" . $item["port"];
        }

        return $list;
    }

    protected function sendRequest($data = array(), $type = "get", $url = ""){
        if(empty($this->url) && empty($url)){
            return false;
        }

        if(empty($url)){
            $url = $this->url;
        }

        if($type == "get"){
            foreach ($data as $param => $value){
                $data[$param] = $param . "=" . $value;
            }

            $data = implode("&", $data);
        }

        $client = new Client();

        if($type == "get"){
            $response = $client->get($url . "?" . $data);
        }else{
            $response = $client->post($url, [
                "form_params" => $data
            ]);
        }

        return $response->getBody()->getContents();
    }

}