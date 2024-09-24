<?php
	namespace PayPal\Auth;
	
	/**
	 * Interface that represents API credentials
	 */
	abstract class IPPCredential {
		protected ?IPPThirdPartyAuthorization $thirdPartyAuthorization = NULL;
		
		/**
		 * @return null|IPPThirdPartyAuthorization
		 */
		public function getThirdPartyAuthorization(): ?IPPThirdPartyAuthorization {
			return $this->thirdPartyAuthorization;
		}
		
		/**
		 * @param $thirdPartyAuthorization
		 *
		 * @return void
		 */
		public function setThirdPartyAuthorization($thirdPartyAuthorization): void {
			$this->thirdPartyAuthorization = $thirdPartyAuthorization;
		}
		
		/**
		 * @return mixed
		 */
		abstract public function validate(): mixed;
	}
