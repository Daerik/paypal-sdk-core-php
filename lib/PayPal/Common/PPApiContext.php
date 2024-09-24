<?php
namespace PayPal\Common;

use PayPal\Core\PPConfigManager;
use PayPal\Core\PPXmlMessage;

/**
 *
 * Container for Call level parameters such as
 * SDK configuration
 */
class PPApiContext
{

    /**
     *
     * @var array Dynamic SDK configuration
     */
    protected $config;

    /**
     * @var PPXmlMessage custom SOAPHeader
     */
    private $SOAPHeader;

    private $httpHeaders;

    /**
     *
     * @param array associative array of HTTP headers to attach to request
     */
    public function setHttpHeaders(array $httpHeaders)
    {
        $this->httpHeaders = $httpHeaders;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     *
     * @param string $name  header name
     * @param string $value header value
     * @param bool   $force if true (default), existing value is overwritten
     */
    public function addHttpHeader(string $name, string $value, bool $force = true)
    {
        if (!$force && array_key_exists($name, $this->httpHeaders)) {
            return $this;
        }
        $this->httpHeaders[$name] = $value;
        return $this;
    }

    /**
     *
     * @param PPXmlMessage $SOAPHeader object to attach to SOAP header
     */
    public function setSOAPHeader(PPXmlMessage $SOAPHeader)
    {
        $this->SOAPHeader = $SOAPHeader;
        return $this;
    }

    /**
     *
     * @return PPXmlMessage
     */
    public function getSOAPHeader()
    {
        return $this->SOAPHeader;
    }

    /**
     *
     * @param array $config SDK configuration parameters
     */
    public function setConfig(array $config)
    {
        $this->config = PPConfigManager::getConfigWithDefaults($config);
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
	
	/**
	 * @param $searchKey
	 *
	 * @return array|false|mixed|string
	 */
	public function get($searchKey)
    {
        if (!isset($this->config)) {
            return PPConfigManager::getInstance()->get($searchKey);
        } elseif (array_key_exists($searchKey, $this->getConfig())) {
                return $this->config[$searchKey];
        }
        return false;
    }

    /**
     *
     * @param null|array $config SDK configuration parameters
     */
    public function __construct(array $config = null)
    {
        $this->config = PPConfigManager::getConfigWithDefaults($config);
    }
}
