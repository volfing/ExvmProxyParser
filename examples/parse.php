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
    "limit" => 100,
    "token" => "demo"
]);

echo "<pre>";
$start = time();
$parser->startParsing();
$parser->truncateList(20);
$parser->checkListForValid("https://www.instagram.com", 3);
$end = time() - $start;
echo "Work completed for {$end} seconds<br><br>";
var_dump($parser->getValidList());