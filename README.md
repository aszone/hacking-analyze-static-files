# Crawler Static File

> ASZone - Crawler - Download File By Local File Download

### Beta

* PHP Avenger sh ( Search Enginer )

### Future Implementation
r
* PHP Avenger bt ( Brute - Force )
* PHP Avenger sca ( State Code Analayse )
* PHP Avenger pwp ( Plugin WordPress )
* PHP Avenger cj ( Component Joomla )

***
#PHP Avenger SH

> Php Avenger sh is a open source tool with ideia **baseaded in fork inurlbr by Cleiton Pinheiro**. Basicaly **PHP Avenger sh** is a tool automates the process of detecting of possibles vunerabilities in using mass scan and check if true or false. Php Avenget utility search enginers with google, bing and others using dorks ( avanced searching ).

## Instalation

The recommended way to install PHP Avenger is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest beta version of Php Avenger SH:

```bash
php composer.phar create-project aszone/crawler/download-file-by-lfd
```

## Basic Usage
```bash
$command=array();
$url="http://www.xxxx.com/download.php?file=../../index.php";
$crawler = new DownloadByLocalFileDownload($command,$url);
$crawler->getAllFiles();

```

## Help and docs
* [Documentation](http://phpavenger.aszone.com.br).
* [Examples](http://phpavenger.aszone.com.br/examples).
* [Videos](http://youtube.com/aszone).
* [Steakoverflow](http://phpavenger.aszone.com.br).

