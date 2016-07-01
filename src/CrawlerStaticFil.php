<?php

/**
 * Created by PhpStorm.
 * User: lenonleite
 * Date: 01/07/16
 * Time: 18:13
 */

namespace Aszone\CrawlerStaticFile;

use Aszone\FakeHeaders\FakeHeaders;
use GuzzleHttp\Client;


class CrawlerStaticFil
{
    public $file;

    public $language;

    public $commandData;

    public function __construct($commandData,$url)
    {
        $this->file =$this->readFile($url);
        $this->language= $this->checkLanguage();
        $this->commandData = array_merge($this->defaultEnterData(), $commandData);
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

    public function getIncludes(){

        echo "<pre>";
        //var_dump($this->file);
        var_dump($this->language);
        $isValid = preg_match_all("/include\((\"|\')(.+?)(\"|\')\)|include (\"|\')(.+?)(\"|\')|include_once\((\"|\')(.+?)(\"|\')\)|include_once (\"|\')(.+?)(\"|\')|require\((\"|\')(.+?)(\"|\')\)|require (\"|\')(.+?)(\"|\')|require_once\((\"|\')(.+?)(\"|\')\)|require_once (\"|\')(.+?)(\"|\')/mi", $this->file, $m);
        if ($isValid) {
            var_dump($m);
        }
    }

    public function checkLanguage(){
        $isValid = preg_match("/<%@|<%|<\?php|<\?=/", $this->file, $m);
        if ($isValid) {
            switch ($m[0]) {
                case "<?php":
                    $result= "php";
                    break;
                case "<?=":
                    $result= "php";
                    break;
                case "<%@":
                    $result= "asp";
                    break;
                case "<%":
                    $result= "asp";
                    break;
            }
            return $result;
        }

        return false;
    }

    protected function readFile($url)
    {
        $header = new FakeHeaders();
        $client = new Client(['defaults' => [
            'headers' => ['User-Agent' => $header->getUserAgent()],
            'proxy' => $this->commandData['tor'],
            'timeout' => 30,
        ],
        ]);
        try {
            return $client->get($url)->getBody()->getContents();
        } catch (\Exception $e) {
            echo '#';
        }

        return false;
    }



}