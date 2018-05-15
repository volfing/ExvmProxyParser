<?php
/**
 * Created by PhpStorm.
 * User: shmax
 * Date: 13.05.2018
 * Time: 11:24
 */

include "../autoload.php";

$parser = new \ExvmProxyParser\ExvmProxyParser();

echo "<pre>";
var_dump($parser->startParsing());