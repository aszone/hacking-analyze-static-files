<?php
/**
 * Created by PhpStorm.
 * User: lenonleite
 * Date: 01/07/16
 * Time: 18:13
 */

namespace Aszone\ReadingStaticFile;

use Aszone\FakeHeaders\FakeHeaders;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WordPress
{
    public $file;

    public $language;

    public $commandData;

    public $url;

    public $urlBaseExploit;

    public $folderSave;

    public $folderDownload;

    public function __construct($commandData)
    {

        $this->commandData = array_merge($this->defaultEnterData(), $commandData);
        //$this->folderDownload = __DIR__."/../../../../results/lfd/";

    }

    private function defaultEnterData()
    {
        $dataDefault['dork'] = false;
        $dataDefault['pl'] = false;
        $dataDefault['tor'] = false;
        $dataDefault['torl'] = false;
        $dataDefault['virginProxies'] = false;
        $dataDefault['proxyOfSites'] = false;

        return $dataDefault;
    }

    //stay
    protected function checkIfWordPressConfigFile($body){
        $isValid = preg_match("/(define\('WP_USE_THEMES', true\);)/", $body, $m);
        if ($isValid) {
           return true;
        }
        return false;
    }

}
