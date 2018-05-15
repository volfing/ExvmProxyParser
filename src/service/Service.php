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

    public function startParse($nextPage = null){
        $nextPage = $this->findNextPage();
        if(!empty($nextPage)){
            $this->startParse($nextPage);
        }
    }

    protected function findNextPage(){
        return null;
    }

    protected function htmlToDomObject($html){
        $dom = new Dom();

        return $dom->load($html);
    }

    protected function findProxiesInDom($dom){
        return null;
    }

    public function getList(){
        $list = [];

        foreach ($this->list as $item) {
            $list[] = $item["protocol"] . "://" . $item["ip"] . ":" . $item["port"];
        }

        return $list;
    }

    protected function sendRequest($data = array(), $type = "get"){
        if(empty($this->url)){
            return false;
        }

        if($type == "get"){
            foreach ($data as $param => $value){
                $data[$param] = $param . "=" . $value;
            }

            $data = implode("&", $data);
        }

        $client = new Client();

        if($type == "get"){
            $response = $client->get($this->url . "?" . $data);
        }else{
            $response = $client->post($this->url, [
                "form_params" => $data
            ]);
        }

        return $response->getBody()->getContents();
    }

}