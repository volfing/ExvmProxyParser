<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:24
 */

include "../autoload.php";

$parser = new \ExvmProxyParser\ExvmProxyParser();
$parser->disableAllServices();
$parser->enableService("FreeproxyListRuService");
$parser->setConfig("FreeproxyListRuService", [
    "limit" => 2000,
    "token" => "demo"
]);

echo "<pre>";
$parser->startParsing();
var_dump($parser->getList());