<?php
namespace PayPal\Core;

use PayPal\Auth\IPPCredential;

/**
 * Encapsulates API request information
 *
 */
class PPRequest
{

    /**
     * Request Object
     *
     * @var object
     */
    private $requestObject;

    /**
     * Optional credentials associated with
     * the request
     * @var IPPCredential
     */
    private $credential;

    /**
     * Transport binding for this request.
     * Can be NVP, SOAP etc
     * @var string
     */
    private $bindingType;

    /**
     *
     * Holder for any binding specific info
     * @var array
     */
    private $bindingInfo = array();
	
	/**
	 * @param $requestObject
	 * @param $bindingType
	 */
	public function __construct($requestObject, $bindingType)
    {
        $this->requestObject = $requestObject;
        $this->bindingType   = $bindingType;
    }
	
	/**
	 * @return object
	 */
	public function getRequestObject()
    {
        return $this->requestObject;
    }
	
	/**
	 * @return string
	 */
	public function getBindingType()
    {
        return $this->bindingType;
    }
	
	/**
	 * @param $name
	 *
	 * @return null|array|mixed
	 */
	public function getBindingInfo($name = null)
    {
        if (isset($name)) {
            return array_key_exists($name, $this->bindingInfo) ? $this->bindingInfo[$name] : null;
        }
        return $this->bindingInfo;
    }

    /**
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addBindingInfo($name, $value)
    {
        $this->bindingInfo[$name] = $value;
    }
	
	/**
	 * @param $credential
	 *
	 * @return void
	 */
	public function setCredential($credential)
    {
        $this->credential = $credential;
    }
	
	/**
	 * @return IPPCredential
	 */
	public function getCredential()
    {
        return $this->credential;
    }
}
