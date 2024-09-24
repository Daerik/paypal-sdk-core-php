<?php
namespace PayPal\Auth;

/**
 * Interface that represents API credentials
 */
abstract class IPPCredential
{
    /**
     *
     * @var IPPThirdPartyAuthorization
     */
    protected $thirdPartyAuthorization;
	
	/**
	 * @param $thirdPartyAuthorization
	 *
	 * @return void
	 */
	public function setThirdPartyAuthorization($thirdPartyAuthorization)
    {
        $this->thirdPartyAuthorization = $thirdPartyAuthorization;
    }
	
	/**
	 * @return IPPThirdPartyAuthorization
	 */
	public function getThirdPartyAuthorization()
    {
        return $this->thirdPartyAuthorization;
    }
	
	/**
	 * @return mixed
	 */
	abstract public function validate();
}
