<?php
namespace rOpenDev\curl\Test;

use rOpenDev\curl\CurlRequest;
use PHPUnit\Framework\TestCase;

final class CurlRequestTest extends TestCase
{
    public function testEffectiveUrl()
    {
        $request = new CurlRequest('http://www.piedweb.com/');
        $request->setDefaultGetOptions()->setReturnHeader()->setDestkopUserAgent()->setEncodingGzip();
        $output = $request->execute();

        $this->assertSame('https://piedweb.com/', $request->getEffectiveUrl());
    }
}
