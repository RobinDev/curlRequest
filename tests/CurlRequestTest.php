<?php
namespace rOpenDev\curl\Test;

use rOpenDev\curl\CurlRequest;

class CurlRequestTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $this->request = new CurlRequest('http://www.google.fr/');
    }
}
