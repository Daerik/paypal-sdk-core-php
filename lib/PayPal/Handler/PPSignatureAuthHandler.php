<?php
	namespace PayPal\Handler;
	
	use PayPal\Auth\PPSubjectAuthorization;
	use PayPal\Auth\PPTokenAuthorization;
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPRequest;
	
	/**
	 *
	 * Adds authentication headers (Platform/Merchant) that are
	 * specific to PayPal's 3-token credentials
	 */
	class PPSignatureAuthHandler
		implements IPPHandler {
		
		/**
		 * @param PPHttpConfig $httpConfig
		 * @param PPRequest    $request
		 * @param              $options
		 *
		 * @return void
		 */
		public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options): void {
			
			$credential     = $request->getCredential();
			$thirdPartyAuth = $credential->getThirdPartyAuthorization();
			
			switch($request->getBindingType()) {
				case 'NV':
					if(!$thirdPartyAuth instanceof PPTokenAuthorization) {
						$httpConfig->addHeader('X-PAYPAL-SECURITY-USERID', $credential->getUserName());
						$httpConfig->addHeader('X-PAYPAL-SECURITY-PASSWORD', $credential->getPassword());
						$httpConfig->addHeader('X-PAYPAL-SECURITY-SIGNATURE', $credential->getSignature());
						$httpConfig->addHeader('X-PAYPAL-SECURITY-SUBJECT', $thirdPartyAuth?->getSubject());
					}
					break;
				case 'SOAP':
					if($thirdPartyAuth instanceof PPTokenAuthorization) {
						$request->addBindingInfo('securityHeader', '<ns:RequesterCredentials/>');
					} else {
						$securityHeader = '<ns:RequesterCredentials><ebl:Credentials>';
						$securityHeader .= '<ebl:Username>' . $credential->getUserName() . '</ebl:Username>';
						$securityHeader .= '<ebl:Password>' . $credential->getPassword() . '</ebl:Password>';
						$securityHeader .= '<ebl:Signature>' . $credential->getSignature() . '</ebl:Signature>';
						if($thirdPartyAuth instanceof PPSubjectAuthorization) {
							$securityHeader .= '<ebl:Subject>' . $thirdPartyAuth->getSubject() . '</ebl:Subject>';
						}
						$securityHeader .= '</ebl:Credentials></ns:RequesterCredentials>';
						$request->addBindingInfo('securityHeader', $securityHeader);
					}
					break;
			}
		}
		
	}
