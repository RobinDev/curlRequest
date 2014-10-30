# Make it easy to request a URL (or few) with PHP cURL
PHP POO cURL wrapper :
* PSR compatible for autoloading,
* Easy install with composer,
* Intuitive and documented
* Light (~8 ko)

##Table of contents
* [Introduction](#introduction)
    * [Description](#description)
* [Installation](#installation)
    * [Packagist](https://packagist.org/packages/ropendev/curl)
* [Examples](#examples)

##Introduction
###Description
Simple cURL class wich transform procedural default cURL options in object. This class is giving some shortcuts like getCookie, setMobileUserAgent, setReferrer... for a more intuitive usage. And you can set cURL's options the old way with CurlRequest::setOpt($option, $value).

All the functions are documented in the class file.

##Installation

[Composer](http://getcomposer.org) is recommended for installation.
In one command line :
```
<<<<<<< HEAD
composer require --dev ropendev/curl dev-master
=======
composer require --dev phpdocumentor/phpdocumentor dev-master
>>>>>>> origin/master
```
Or via editting your `composer.json`
```json
{
    "require": {
        "ropendev/curl": "dev-master"
    }
}
```
```
composer update
```

##Examples

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

###Single request with proxie, post and cookie
```php
<?php
use \rOpenDev\curl\CurlRequest;

$request = new CurlRequest('http://www.bing.com/');
$request->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip()->execute();

$r2 = new CurlRequest('http://www.bing.com/search?q=curl+request+php');
echo $r2->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip()->setCookie($request->getCookie())->setProxy('domain:port:user:password')->execute();
```
### All the options
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

    ->setProxy(string 'httpproxyhost:port:username:passwrd')

    ->setReturnHeaderOnly()

    ->setUrl($url, $resetPreviousOptions)

string $r->execute(); // Return contents from the url

array  $r->getHeader(); // Return Response Header

string $r->getCookies();

$r->hasError|getError|getInfo(); // Equivalent to curl function curl_errno|curl_error|curl_getinfo();

```

Enjoy :)

Licence : WTFPL (see LICENCE file)
