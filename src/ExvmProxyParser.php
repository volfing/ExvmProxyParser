<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:25
 */

namespace ExvmProxyParser;

class ExvmProxyParser
{
    public function startParsing($limit = 1000){
        $service = new Service\FreeProxySaleComService();

        $service->startParse();

        $list = $service->getList();

        return array_slice($list, 0, $limit);
    }
}