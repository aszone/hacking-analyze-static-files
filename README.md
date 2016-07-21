# Crawler - Download File By Local File Download

> ASZone - Crawler - Download File By Local File Download

### Beta
> Download File By Local File Download is a open source tool for download files extracted of system with fail of Local File Download / Path Director

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
$crawler = new DownloadByLocalFileDownload($command);
$url="http://www.xxxx.com/download.php?file=../../index.php";
$crawler->getAllFiles($url);

```

## Help and docs
* [Documentation](http://phpavenger.aszone.com.br).
* [Examples](http://phpavenger.aszone.com.br/examples).
* [Videos](http://youtube.com/aszone).
* [Steakoverflow](http://phpavenger.aszone.com.br).

