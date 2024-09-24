<?php
namespace PayPal\Core;

use PayPal\Exception\PPConfigurationException;

class PPHttpConfig
{

    /**
     * Some default options for curl
     * These are typically overridden by PPConnectionManager
     */
    public static array $DEFAULT_CURL_OPTS = array(
      CURLOPT_SSLVERSION      => 6,
      CURLOPT_CONNECTTIMEOUT  => 10,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_TIMEOUT         => 60,  // maximum number of seconds to allow cURL functions to execute
      CURLOPT_USERAGENT       => 'PayPal-PHP-SDK',
      CURLOPT_HTTPHEADER      => array(),
      CURLOPT_SSL_VERIFYHOST  => 2,
      CURLOPT_SSL_VERIFYPEER  => 1,
      CURLOPT_SSL_CIPHER_LIST => 'TLSv1',
    );
	
	public const string HEADER_SEPARATOR = ';';
	public const string HTTP_GET         = 'GET';
	public const string HTTP_POST = 'POST';

    private array $headers = array();

    private array $curlOptions;

    private ?string $url;

    private string $method;
    /***
     * Number of times to retry a failed HTTP call
     */
    private $retryCount;

    /**
     *
     * @param null|string $url
     * @param string      $method  HTTP method (GET, POST etc) defaults to POST
     * @param array       $configs All Configurations
     */
    public function __construct(string $url = null, string $method = self::HTTP_POST, array $configs = array())
    {
        $this->url         = $url;
        $this->method      = $method;
        $this->curlOptions = $this->getHttpConstantsFromConfigs($configs, 'http.') + self::$DEFAULT_CURL_OPTS;
        // Update the Cipher List based on OpenSSL or NSS settings
        $curl       = curl_version();
        $sslVersion = $curl['ssl_version'] ?? '';
        if (substr_compare($sslVersion, "NSS/", 0, strlen("NSS/")) === 0) {
            //Remove the Cipher List for NSS
            $this->removeCurlOption(CURLOPT_SSL_CIPHER_LIST);
        }
    }
	
	/**
	 * @return null|string
	 */
	public function getUrl(): ?string {
        return $this->url;
    }
	
	/**
	 * @return string
	 */
	public function getMethod(): string {
        return $this->method;
    }
	
	/**
	 * @return array
	 */
	public function getHeaders(): array {
        return $this->headers;
    }
	
	/**
	 * @param $name
	 *
	 * @return null|mixed
	 */
	public function getHeader($name): mixed {
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return null;
    }
	
	/**
	 * @param $url
	 *
	 * @return void
	 */
	public function setUrl($url): void {
        $this->url = $url;
    }
	
	/**
	 * @param array $headers
	 *
	 * @return void
	 */
	public function setHeaders(array $headers): void {
        $this->headers = $headers;
    }
	
	/**
	 * @param $name
	 * @param $value
	 * @param true $overWrite
	 *
	 * @return void
	 */
	public function addHeader($name, $value, true $overWrite = true): void {
        if (!array_key_exists($name, $this->headers) || $overWrite) {
            $this->headers[$name] = $value;
        } else {
            $this->headers[$name] = $this->headers[$name] . self::HEADER_SEPARATOR . $value;
        }
    }
	
	/**
	 * @param $name
	 *
	 * @return void
	 */
	public function removeHeader($name): void {
        unset($this->headers[$name]);
    }
	
	/**
	 * @return array|int[]
	 */
	public function getCurlOptions(): array {
        return $this->curlOptions;
    }
	
	/**
	 * @param $name
	 * @param $value
	 *
	 * @return void
	 */
	public function addCurlOption($name, $value): void {
        $this->curlOptions[$name] = $value;
    }

    /**
     * Removes a curl option from the list
     *
     * @param $name
     */
    public function removeCurlOption($name): void {
        unset($this->curlOptions[$name]);
    }
	
	/**
	 * @param $options
	 *
	 * @return void
	 */
	public function setCurlOptions($options): void {
        $this->curlOptions = $options;
    }

    /**
     * Set ssl parameters for certificate based client authentication
     *
     * @param string $certPath - path to client certificate file (PEM formatted file)
     */
    public function setSSLCert(string $certPath, $passPhrase = null): void {
        $this->curlOptions[CURLOPT_SSLCERT] = realpath($certPath);
        if (isset($passPhrase) && trim($passPhrase) != "") {
            $this->curlOptions[CURLOPT_SSLCERTPASSWD] = $passPhrase;
        }
    }

    /**
     * Set connection timeout in seconds
     *
     * @param int $timeout
     */
    public function setHttpConnectionTimeout(int $timeout): void {
        $this->curlOptions[CURLOPT_CONNECTTIMEOUT] = $timeout;
    }

    /**
     * Set timeout in seconds
     *
     * @param int $timeout
     */
    public function setHttpTimeout(int $timeout): void {
        $this->curlOptions[CURLOPT_TIMEOUT] = $timeout;
    }

    /**
     * Set HTTP proxy information
     *
     * @param string $proxy
     *
     * @throws PPConfigurationException
     */
    public function setHttpProxy(string $proxy): void {
        $urlParts = parse_url($proxy);
        if (!$urlParts || !array_key_exists("host", $urlParts)) {
            throw new PPConfigurationException("Invalid proxy configuration " . $proxy);
        }

        $this->curlOptions[CURLOPT_PROXY] = $urlParts["host"];
        if (isset($urlParts["port"])) {
            $this->curlOptions[CURLOPT_PROXY] .= ":" . $urlParts["port"];
        }
        if (isset($urlParts["user"])) {
            $this->curlOptions[CURLOPT_PROXYUSERPWD] = $urlParts["user"] . ":" . $urlParts["pass"];
        }
    }
	
	/**
	 * @param $retryCount
	 */
    public function setHttpRetryCount($retryCount): void {
        $this->retryCount = $retryCount;
    }
	
	/**
	 * @return mixed
	 */
	public function getHttpRetryCount(): mixed {
        return $this->retryCount;
    }

    /**
     * Sets the User-Agent string on the HTTP request
     *
     * @param string $userAgentString
     */
    public function setUserAgent(string $userAgentString): void {
        $this->curlOptions[CURLOPT_USERAGENT] = $userAgentString;
    }

    /**
     * Retrieves an array of constant key, and value based on Prefix
     *
     * @param array $configs
     * @param       $prefix
     *
     * @return array
     */
    public function getHttpConstantsFromConfigs(array $configs = array(), $prefix): array {
        $arr = array();
        if ($prefix != null && is_array($configs)) {
            foreach ($configs as $k => $v) {
                // Check if it startsWith
                if (str_starts_with($k, $prefix)) {
                    $newKey = ltrim($k, $prefix);
                    if (defined($newKey)) {
                        $arr[constant($newKey)] = $v;
                    }
                }
            }
        }
        return $arr;
    }
}
