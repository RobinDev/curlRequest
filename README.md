# Make it easy to request a URL (or few) with PHP cURL

PHP POO cURL wrapper :
* PSR compliant,
* Easy install with composer,
* Intuitive and documented
* Light (~8 ko)

##Introduction

Simple cURL class wich transform procedural default cURL options in object. This class is giving some shortcuts like getCookie, setMobileUserAgent, setReferrer... for a more intuitive usage. And you can set cURL's options the old way with CurlRequest::setOpt($option, $value).

All the functions are documented in the class file.

##Installation

Via [Packagist](https://packagist.org/packages/ropendev/curl) :
```bash
composer require ropendev/curl
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

array  $r->getHeader($arrayFormatted = true); // Return Response Header in an array (or in a string if $arrayFormatted is set to false)

string $r->getCookies();

string $r->getEffectiveUrl();

$r->hasError|getError|getInfo(); // Equivalent to curl function curl_errno|curl_error|curl_getinfo();

```

## License

MIT (see the `LICENSE` file for details)

##Contributors

Before to submit a pull request, check test is still passing (`phpunit`).

* Original author : [Robin (SEO Consultant in Marseille)](http://www.robin-d.fr/).
* [Pied Web](https://piedweb.com)
* ...


[![Latest Version](https://img.shields.io/github/tag/RobinDev/curlRequest.svg?style=flat&label=release)](https://github.com/RobinDev/curlRequest/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](https://github.com/RobinDev/curlRequest/LICENSE.md)
[![Build Status](https://img.shields.io/travis/RobinDev/curlRequest/master.svg?style=flat)](https://travis-ci.org/RobinDev/curlRequest)
[![Quality Score](https://img.shields.io/scrutinizer/g/RobinDev/curlRequest.svg?style=flat)](https://scrutinizer-ci.com/g/RobinDev/curlRequest)
[![Total Downloads](https://img.shields.io/packagist/dt/ropendev/curl.svg?style=flat)](https://packagist.org/packages/ropendev/curl)
