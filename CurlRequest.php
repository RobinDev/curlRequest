<?php


/**
 * PHP POO cURL wrapper (PSR compatible for autoloading).
 *
 *
 * PHP version 5
 *
 * @author     Original Author Robin <contact@robin-d.fr>
 * @link       https://github.com/RobinDev/curlRequest
 * @since      File available since Release 2014.04.29
 */

namespace rOpenDev\curl;

class CurlRequest {

    protected $ch, $gzip=false,$rHeader=false;

    function __construct($url) {
        $this->ch = curl_init($url);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    function setOpt($option, $value) {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    function setDefaultGetOptions($connectTimeOut = 5, $timeOut = 10, $dnsCacheTimeOut = 600, $followLocation = true, $maxRedirs = 5) {
        $this->setOpt(CURLOPT_AUTOREFERER, true)
             ->setOpt(CURLOPT_FOLLOWLOCATION,    $followLocation)
             ->setOpt(CURLOPT_MAXREDIRS,         $maxRedirs)
             ->setOpt(CURLOPT_CONNECTTIMEOUT,    $connectTimeOut)
             ->setOpt(CURLOPT_DNS_CACHE_TIMEOUT, $dnsCacheTimeOut)
             ->setOpt(CURLOPT_TIMEOUT,           $timeOut)
             ->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        return $this;
    }

    function setDefaultSpeedOptions() {
        $this->setOpt(CURLOPT_SSL_VERIFYHOST, false)
             ->setOpt(CURLOPT_SSL_VERIFYPEER, false)
             ->setOpt(CURLOPT_HEADER, false)
             ->setOpt(CURLOPT_COOKIE, false)
             ->setOpt(CURLOPT_MAXREDIRS, 1);
        return $this;
    }

    function setReturnHeader() {
        $this->setOpt(CURLOPT_HEADER, true);
        $this->rHeader = true;
        return $this;
    }

    /**
     * @param string $cookie
     */
    function setCookie($cookie) {
        $this->setOpt(CURLOPT_COOKIE, $cookie);
        return $this;
    }

    function setReferrer($referrer) {
        $this->setOpt(CURLOPT_REFERER, $referrer);
        return $this;
    }

    function setUserAgent($ua) {
        $this->setOpt(CURLOPT_USERAGENT, $ua);
        return $this;
    }

    function setDestkopUserAgent() {
        $this->setUserAgent('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0');
        return $this;
    }

    function setMobileUserAgent() {
        $this->setUserAgent('Mozilla/5.0 (Linux; U; Android 2.2.1; en-ca; LG-P505R Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1');
        return $this;
    }

    function setLessJsUserAgent() {
        $this->setUserAgent('NokiaN70-1/5.0609.2.0.1 Series60/2.8 Profile/MIDP-2.0 Configuration/CLDC-1.1 UP.Link/6.3.1.13.0');
        return $this;
    }

    function setPost($post_array) {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($post_array));
        return $this;
    }

    function setEncodingGzip() {
        $this->setOpt(CURLOPT_ENCODING, 'gzip, deflate');
        $this->gzip = true;
        return $this;
    }

    function setProxy($proxy) {
        $proxy = explode(':', $proxy);
        $this->setOpt(CURLOPT_HTTPPROXYTUNNEL, true);
        $this->setOpt(CURLOPT_PROXY, $proxy[0].':'.$proxy[1]);
        if(isset($proxy[2])) {
            $this->setOpt(CURLOPT_PROXYUSERPWD, $proxy[2].':'.$proxy[3]);
        }
        return $this;
    }

    function execute() {

        $html = curl_exec($this->ch);
        //if($this->gzip) $html = gzdecode ($html);

        if($this->rHeader) {
            $this->header = substr($html, 0, $sHeader=curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
            $html = substr($html, $sHeader);
        }

        return $html;
    }

    function getHeader() {
        if(isset($this->header))
            return $this->http_parse_headers($this->header);
    }

    /**
     * @param string $format str for function returns a string else an array
     */
    function getCookies() {
        if(isset($this->header)) {
            $header = $this->getHeader();
            if(isset($header['Set-Cookie'])) {
                return is_array($header['Set-Cookie']) ? implode('; ', $header['Set-Cookie']) : $header['Set-Cookie'];
            }
        }
    }

    function hasError() {
        return curl_errno($this->ch);
    }

    function getErrors() {
        return curl_error($this->ch);
    }


    function getInfo() {
        return curl_getinfo($this->ch);
    }

    public function __destruct() {
        curl_close($this->ch);
    }

    /**
     * @source http://www.php.net/manual/en/function.http-parse-headers.php#112917
     */
    function http_parse_headers($raw_headers) {
        $headers = array();
        $key = '';

        foreach(explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1]))  {
                if (!isset($headers[$h[0]]))
                    $headers[$h[0]] = trim($h[1]);
                elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                }
                else {
                    $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                }

                $key = $h[0];
            }
            else {
                if (substr($h[0], 0, 1) == "\t")
                    $headers[$key] .= "\r\n\t".trim($h[0]);
                elseif (!$key)
                    $headers[0] = trim($h[0]);trim($h[0]);
            }
        }

        return $headers;
    }
}
