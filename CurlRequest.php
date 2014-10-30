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

	/**
	 * If set to true (via setReturnHeaderOnly), headers only are returned (via execute)
	 * @var bool
	 */
	protected $headerOnly = false;

	/**
	 * @var Ressource containing cURL Session
	 */
    public static $ch;

    protected $gzip=false,$rHeader=false;

    function __construct($url, $usePreviousSession=false)
    {
		if($usePreviousSession === false || !isset(self::$ch)) {
			self::$ch = curl_init($url);
		}
		else {
			curl_reset(self::$ch);
			$this->setOpt(CURLOPT_URL, $url);
		}
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Change the URL to cURL
     *
     * @param $url string URL to cURL
     * @param $reset bool True if you want to remove cURLs params setted before calling this function
     *
     * @return self
     */
    function setUrl($url, $reset = false)
    {
		if($resest) {
			curl_reset(self::$ch);
		}
		$this->setOpt(CURLOPT_URL, $url);
		return $this;
	}

	/**
	 * Add a cURL's option
	 *
	 * @param $option const cURL Predefined Constant
	 * @param $value mixed
	 *
	 * @return self
	 */
    function setOpt($option, $value)
    {
        curl_setopt(self::$ch, $option, $value);
        return $this;
    }

	/**
	 * A short way to set some classic options to cURL a web page
	 *
	 * @return self
	 */
    function setDefaultGetOptions($connectTimeOut = 5, $timeOut = 10, $dnsCacheTimeOut = 600, $followLocation = true, $maxRedirs = 5)
    {
        $this->setOpt(CURLOPT_AUTOREFERER, true)
             ->setOpt(CURLOPT_FOLLOWLOCATION,    $followLocation)
             ->setOpt(CURLOPT_MAXREDIRS,         $maxRedirs)
             ->setOpt(CURLOPT_CONNECTTIMEOUT,    $connectTimeOut)
             ->setOpt(CURLOPT_DNS_CACHE_TIMEOUT, $dnsCacheTimeOut)
             ->setOpt(CURLOPT_TIMEOUT,           $timeOut)
             ->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        return $this;
    }

	/**
	 * A short way to set some classic options to cURL a web page quickly (but lossing some data like header, cookie...)
	 *
	 * @return self
	 */
    function setDefaultSpeedOptions()
    {
        $this->setOpt(CURLOPT_SSL_VERIFYHOST, false)
             ->setOpt(CURLOPT_SSL_VERIFYPEER, false)
             ->setOpt(CURLOPT_HEADER, false)
             ->setOpt(CURLOPT_COOKIE, false)
             ->setOpt(CURLOPT_MAXREDIRS, 1);
        return $this;
    }

	/**
	 * Call it if you want header informations.
	 * After self::execute(), you would have this informations with getHeader();
	 *
	 * @return self
	 */
    function setReturnHeader()
    {
        $this->setOpt(CURLOPT_HEADER, true);
        $this->rHeader = true;
        return $this;
    }

	/**
	 * Call it if you want header informations only.
	 * After self::execute(), you would have this informations with getHeader();
	 *
	 * @return self
	 */
    function setReturnHeaderOnly()
    {
		$this->headerOnly = true;
		$this->setOpt(CURLOPT_HEADER,	true)
		     ->setOpt(CURLOPT_NOBODY,	true);
		return $this;
	}

    /**
     * An self::setOpt()'s alias to add a cookie to your request
     *
     * @param string $cookie
     *
	 * @return self
     */
    function setCookie($cookie)
    {
        $this->setOpt(CURLOPT_COOKIE, $cookie);
        return $this;
    }

	/**
     * An self::setOpt()'s alias to add a referrer to your request
     *
     * @param string $referrer
     *
	 * @return self
     */
    function setReferrer($referrer)
    {
        $this->setOpt(CURLOPT_REFERER, $referrer);
        return $this;
    }

	/**
     * An self::setOpt()'s alias to add an user-agent to your request
     *
     * @param string $ua
     *
	 * @return self
     */
    function setUserAgent($ua)
    {
        $this->setOpt(CURLOPT_USERAGENT, $ua);
        return $this;
    }

	/**
     * An self::setUserAgent()'s alias to add an user-agent wich correspond to a Destkop PC
     *
	 * @return self
     */
    function setDestkopUserAgent()
    {
        $this->setUserAgent('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0');
        return $this;
    }

	/**
     * An self::setUserAgent()'s alias to add an user-agent wich correspond to a mobile
     *
	 * @return self
     */
    function setMobileUserAgent()
    {
        $this->setUserAgent('Mozilla/5.0 (Linux; U; Android 2.2.1; en-ca; LG-P505R Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1');
        return $this;
    }

	/**
     * An self::setUserAgent()'s alias to add an user-agent wich correspond to a webrowser without javascript
     *
	 * @return self
     */
    function setLessJsUserAgent()
    {
        $this->setUserAgent('NokiaN70-1/5.0609.2.0.1 Series60/2.8 Profile/MIDP-2.0 Configuration/CLDC-1.1 UP.Link/6.3.1.13.0');
        return $this;
    }

	/**
	 * A short way to set post's options to cURL a web page
	 *
	 * @param $post_array array containing data (key=>vvalue) to post
	 *
	 * @return self
	 */
    function setPost($post_array)
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, http_build_query($post_array));
        return $this;
    }

	/**
	 * If you want to request the URL and hope get the result gzipped.
	 * The output will be automatically uncompress with execute();
	 *
	 * @return self
	 */
    function setEncodingGzip()
    {
        $this->setOpt(CURLOPT_ENCODING, 'gzip, deflate');
        $this->gzip = true;
        return $this;
    }

	/**
	 * If you want to request the URL with a http proxy (public or private)
	 *
	 * @param $proxy string IP:PORT[:LOGIN:PASSWORD]
	 *
	 * @return self
	 */
    function setProxy($proxy)
    {
		if(!empty($proxy)) {
			$proxy = explode(':', $proxy);
			$this->setOpt(CURLOPT_HTTPPROXYTUNNEL, true);
			$this->setOpt(CURLOPT_PROXY, $proxy[0].':'.$proxy[1]);
			if(isset($proxy[2])) {
				$this->setOpt(CURLOPT_PROXYUSERPWD, $proxy[2].':'.$proxy[3]);
			}
		}
		return $this;
    }

	/**
	 * Execute the request
	 *
	 * @return string wich is the request's result without the header (you can obtain with self::getHeader() now)
	 */
    function execute()
    {
		if($this->headerOnly) {
			return $this->header = curl_exec(self::$ch);
		}
		$html = curl_exec(self::$ch);
		//if($this->gzip) $html = gzdecode ($html);

		if($this->rHeader) {
			$this->header = substr($html, 0, $sHeader=curl_getinfo(self::$ch, CURLINFO_HEADER_SIZE));
			$html = substr($html, $sHeader);
		}
        return $html;
    }

	/**
	 * Return header's data return by the request
	 *
	 * @return array containing header's data
	 */
    function getHeader()
    {
        if(isset($this->header))
            return $this->http_parse_headers($this->header);
    }

    /**
     * Return the cookie(s) returned by the request (if there are)
     *
     * @param string $format str for function returns a string else an array
     *
     * @return null|array containing the cookies
     */
    function getCookies()
    {
        if(isset($this->header)) {
            $header = $this->getHeader();
            if(isset($header['Set-Cookie'])) {
                return is_array($header['Set-Cookie']) ? implode('; ', $header['Set-Cookie']) : $header['Set-Cookie'];
            }
        }
    }

	/**
	 * Return the last error number (curl_errno)
	 *
	 * @return int the error number or 0 (zero) if no error occurred.
	 */
    function hasError()
    {
        return curl_errno(self::$ch);
    }

	/**
	 * Return a string containing the last error for the current session (curl_error)
	 *
	 * @return string the error message or '' (the empty string) if no error occurred.
	 */
    function getErrors()
    {
        return curl_error(self::$ch);
    }

	/**
	 * Get information regarding the request
	 *
	 * @return bool|array an associative array with the following elements (which correspond to opt), or FALSE on failure
	 */
    function getInfo()
    {
        return curl_getinfo(self::$ch);
    }

    public function __destruct()
    {
        //curl_close(self::$ch);
    }

    /**
     * Parse HTTP headers (php HTTP functions but generally, this packet isn't installed)
     * @source http://www.php.net/manual/en/function.http-parse-headers.php#112917
     *
     * @param $raw_headers string containing HTTP headers
     *
     * @return bool|array an array on success or FALSE on failure.
     */
    function http_parse_headers($raw_headers)
    {
		if (function_exists('http_parse_headers')) {
			http_parse_headers($raw_headers);
		}

        $headers = [];
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
                    $headers[$h[0]] = array_merge([$headers[$h[0]]], [trim($h[1])]);
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
