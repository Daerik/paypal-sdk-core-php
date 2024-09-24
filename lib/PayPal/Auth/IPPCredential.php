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
    protected IPPThirdPartyAuthorization $thirdPartyAuthorization;
	
	/**
	 * @param $thirdPartyAuthorization
	 *
	 * @return void
	 */
	public function setThirdPartyAuthorization($thirdPartyAuthorization): void {
        $this->thirdPartyAuthorization = $thirdPartyAuthorization;
    }
	
	/**
	 * @return IPPThirdPartyAuthorization
	 */
	public function getThirdPartyAuthorization(): IPPThirdPartyAuthorization {
        return $this->thirdPartyAuthorization;
    }
	
	/**
	 * @return mixed
	 */
	abstract public function validate(): mixed;
}
