# Make it easy to request a URL (or few) with PHP cURL

[![Quality Score](https://img.shields.io/scrutinizer/g/RobinDev/curlRequest.svg?style=flat-square)](https://scrutinizer-ci.com/g/RobinDev/curlRequest)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/388f6562-32ae-454d-97ce-4d1bed306ee3/mini.png)](https://insight.sensiolabs.com/projects/388f6562-32ae-454d-97ce-4d1bed306ee3)

PHP POO cURL wrapper :
* PSR compliant (2 Coding Style, 4 Autoloading),
* Easy install with composer,
* Intuitive and documented
* Light (~8 ko)

##Table of contents
* [Introduction](#introduction)
* [Installation](#installation)
    * [Packagist](https://packagist.org/packages/ropendev/curl)
* [Usage](#usage)
* [Documentation](#documentation)
* [Lisense](#lisense)
* [Contributors](#contributors)

##Introduction

Simple cURL class wich transform procedural default cURL options in object. This class is giving some shortcuts like getCookie, setMobileUserAgent, setReferrer... for a more intuitive usage. And you can set cURL's options the old way with CurlRequest::setOpt($option, $value).

All the functions are documented in the class file.

##Installation

[Composer](http://getcomposer.org) is recommended for installation.

In one command line :
```bash
composer require ropendev/curl
```

Or via editting your `composer.json`
```json
{
    "require": {
        "ropendev/curl": "dev-master"
    }
}
```
```bash
composer update
```

##Usage

###Single request
```php
<?php
use \rOpenDev\curl\CurlRequest;

$request = new CurlRequest('http://www.example.org/');
$request->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip();
$output = $curl->execute();

echo $output;
```
The above example will output the contet from example.org.

###Single request with proxy, post and cookie
```php
<?php
use \rOpenDev\curl\CurlRequest;

$request = new CurlRequest('http://www.bing.com/');
$request->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip()->execute();

$r2 = new CurlRequest('http://www.bing.com/search?q=curl+request+php');
echo $r2->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip()->setCookie($request->getCookie())->setProxy('domain:port:user:password')->execute();
```
##Documentation

Are listed every public method.

```php
<?php
use rOpenDev\curl\CurlRequest;

$r = new CurlRequest('scheme://host/path');
$r
    ->setOpt(CURLOPT_*, mixed 'value')

	// Preselect Options to avoid eternity wait
    ->setDefaultGetOptions($connectTimeOut = 5, $timeOut = 10, $dnsCacheTimeOut = 600, $followLocation = true, $maxRedirs = 5)
    ->setDefaultSpeedOptions()

    ->setReturnHeader() // If you want to get the header with getHeader()
    ->setCookie(string $cookie)
    ->setReferer(string $url)
    ->setUserAgent(string $ua)
        ->setDestkopUserAgent()
        ->setMobileUserAgent()
        ->setLessJsUserAgent()

    ->setPost(array $post)

    ->setEncodingGzip()

    ->setProxy(string '[scheme]proxy-host:port[:username:passwrd]') // Scheme, username and passwrd are facultatives. Default Scheme is http://

    ->setReturnHeaderOnly()

    ->setUrl($url, $resetPreviousOptions)

string $r->execute();   // Return contents from the url

array  $r->getHeader(); // Return Response Header

string $r->getCookies();

$r->hasError|getError|getInfo(); // Equivalent to curl function curl_errno|curl_error|curl_getinfo();

```

## License

MIT (see the `LICENSE` file for details)

##Contributors

* Original author : [Robin (SEO Consultant in Marseille)](http://www.robin-d.fr/).
* ...
