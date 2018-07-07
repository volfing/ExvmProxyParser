<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 15.05.2018
 * Time: 10:28
 */

namespace ExvmProxyParser\Service;


class FreeProxySaleComService extends Service
{
    protected $url = "https://free.proxy-sale.com/";

    protected function findProxiesInDom($dom)
    {
        $table = $dom->find("table.table");

        $rows = $table->find("tbody tr");

        $list = [];

        foreach ($rows as $row) {
            $proxy = trim($row->find(".bg-data")->text);
            $port = $row->find(".port img")[0]->getAttribute("src");
            $port = explode("=", $port)[1];
            $type = $row->find(".type a")[0]->text;

            if($type != "http" && $type != "https"){
                continue;
            }

            $list[] = [
                "ip" => $proxy,
                "port" => $port,
                "protocol" => $type
            ];
        }

        return $list;
    }

    protected function findNextPage()
    {
        $res =  $this->sendRequest();

        $dom = $this->htmlToDomObject($res);

        $links = $dom->find(".pagination a");

        foreach ($links as $link){
            if((int)$link->text > $this->currentPage){
                $this->url = preg_replace("/\?pg\=(.*)/", "", $this->url);
                return $this->url . "?pg=" . (int)$link->text;
            }
        }

        return null;
    }
}