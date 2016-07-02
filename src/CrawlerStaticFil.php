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

    public $url;

    public $urlBaseExploit;

    public function __construct($commandData,$url)
    {
        $this->commandData = array_merge($this->defaultEnterData(), $commandData);
        $this->url =$url;
        $this->file =$this->readFile($url);
        $this->language= $this->checkLanguage();
        $this->urlBaseExploit = $this->getBaseExploit();


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

    public function getAllFiles(){

        $files=$this->getIncludes($this->file);

        echo $this->url."\n";
        if($files){
            foreach($files as $file){
                echo $url=$this->generateUrl($file)."\n";
                $body = $this->readFile($url);
                $newFiles=$this->getIncludes($body);
                if($newFiles){
                    $files=array_merge($newFiles,$files);
                }

            }
        }
        $files[]=$this->url;
        var_dump($files);
        exit();

    }

    private function generateUrl($file){
        //echo $file."**";
        return str_replace("######",$file,$this->getBaseExploit());
    }

    public function getIncludes($file){

        $isValid = preg_match_all("/include\((\"|\')(.+?)(\"|\')\)|include (\"|\')(.+?)(\"|\')|include_once\((\"|\')(.+?)(\"|\')\)|include_once (\"|\')(.+?)(\"|\')|require\((\"|\')(.+?)(\"|\')\)|require (\"|\')(.+?)(\"|\')|require_once \((\"|\')(.+?)(\"|\')\)|require_once (\"|\')(.+?)(\"|\')/i", $file, $m);
        //var_dump($file);
        if ($isValid) {
            $results=$this->sanitazePregMatchAll($m);
            return $results;
        }

        return false;

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

    protected function sanitazePregMatchAll($matchs){

        $result[0]=$matchs[0];
        foreach($matchs as $match){
            foreach($match as $keyValueMatch=>$valueMatch){
                if(!empty($valueMatch) AND $valueMatch!="'" AND $valueMatch!='"'){
                    $result[1][$keyValueMatch]=$valueMatch;
                }
            }
        }
        return $result[1];
    }

    protected function getBaseExploit()
    {
        $validResult = preg_match("/." . $this->language . ".*?(=|\/)(.+?)." . $this->language . "/i", $this->url, $m);
        if ($validResult) {
            $baseUrlTrash = $m[2] . "." . $this->language;
            $explodeBar = explode("/", $baseUrlTrash);
            if (!isset($explodeBar[1])) {
                return str_replace($baseUrlTrash, "######", $this->url);
            }
            array_pop($explodeBar);
            return str_replace($baseUrlTrash, implode("/", $explodeBar) . "/######", $this->url);

        }
    }


}