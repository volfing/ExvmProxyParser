<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:46
 */

namespace ExvmProxyParser\Service;


class SpysOneService extends Service
{
    protected $url = "http://spys.one/proxies/";

    public function startParse($nextPage = null)
    {
        if(!empty($nextPage)){
            $this->url = $nextPage;
        }

        $response = $this->sendRequest();

        parent::startParse();

        $dom = $this->htmlToDomObject($response);
        $proxies = $this->findProxiesInDom($dom);
        $this->list = array_merge($this->list, $proxies);
    }

    protected function findProxiesInDom($dom)
    {
        $tables = $dom->find("table");

        if(empty($tables[1])){
            return [];
        }

        $rows = $tables[1]->find("tr table tr");

        $list = array();

        foreach ($rows as $row){
            if(empty($row->getAttribute("onmouseover"))){
                continue;
            }

            $proxy = $row->find("td");

            var_dump($proxy[0]->outerHtml);

            /*$ip = $proxy[1]->outerHtml;
            $port = $proxy[2]->outerHtml;

            var_dump($ip);
            var_dump($port);*/
        }

        return $list;
    }

}