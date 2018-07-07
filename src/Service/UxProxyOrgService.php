<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 05/07/2018
 * Time: 19:24
 */

namespace ExvmProxyParser\Service;

/**
 * Class UxProxyOrgService
 * @package ExvmProxyParser\Service
 */
class UxProxyOrgService extends Service
{
    /**
     * UxProxyOrgService constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (empty($config['url'])) {
            throw new \Exception('Config property \'url\' must be configured.');
        }
        $this->url = $config['url'];
        parent::__construct($config);
    }

    public function startParse($nextPage = null, $prepareDom = false)
    {
        parent::startParse($nextPage, $prepareDom);
    }

    protected function findProxiesInDom($dom)
    {
        $lines = explode("\n", $dom);
        $lines = array_filter($lines);

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