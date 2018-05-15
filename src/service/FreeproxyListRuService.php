<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 15.05.2018
 * Time: 15:28
 */

namespace ExvmProxyParser\Service;


class FreeproxyListRuService extends Service
{
    protected $url = "http://www.freeproxy-list.ru/api/proxy?token={token}&count=10000&accessibility=60";
    private  $token;

    public function __construct($config)
    {
        $this->token = !empty($config["token"]) ? $config["token"] : "demo";

        parent::__construct($config);
    }

    public function startParse($nextPage = null, $prepareDom = false)
    {
        if(strpos($this->url, "{token}") === false){
           $this->url = preg_replace("/\?token\=(.*)&count/", "?token=" . $this->token . "&count", $this->url);
        }else{
            $this->url = str_replace("{token}", $this->token, $this->url);
        }

        parent::startParse($nextPage, $prepareDom);
    }

    protected function findProxiesInDom($dom)
    {
        $lines = explode("\n", $dom);

        $list = [];

        foreach ($lines as $line) {
            list($ip, $port) = explode(":", $line);
            $list[] = [
                "ip" => $ip,
                "port" => $port,
                "protocol" => "http"
            ];
        }

        return $list;
    }
}