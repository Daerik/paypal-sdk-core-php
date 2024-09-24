<?php
	namespace PayPal\Handler;
	
	use PayPal\Auth\Oauth\AuthSignature;
	use PayPal\Auth\PPCertificateCredential;
	use PayPal\Auth\PPSignatureCredential;
	use PayPal\Auth\PPTokenAuthorization;
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPRequest;
	use PayPal\Exception\OAuthException;
	use PayPal\Exception\PPInvalidCredentialException;
	
	/**
	 *
	 * Determines which authentication handler to run based
	 * on credential passed in.
	 *
	 * Also handles PayPal third party authentication (Permissions API).
	 *
	 */
	class PPAuthenticationHandler
		implements IPPHandler {
		
		/**
		 * @param PPHttpConfig $httpConfig
		 * @param PPRequest    $request
		 * @param              $options
		 *
		 * @throws PPInvalidCredentialException
		 * @throws OAuthException
		 */
		public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options): void {
			$credential     = $request->getCredential();
			$thirdPartyAuth = $credential->getThirdPartyAuthorization();
			if($thirdPartyAuth instanceof PPTokenAuthorization) {
				$authSignature = AuthSignature::generateFullAuthString($credential->getUsername(),
					$credential->getPassword(), $thirdPartyAuth->getAccessToken(), $thirdPartyAuth->getTokenSecret(),
					$httpConfig->getMethod(), $httpConfig->getUrl());
				if(isset($options['port']) &&
				   ($options['port'] == 'PayPalAPI' || $options['port'] == 'PayPalAPIAA')
				) {
					$httpConfig->addHeader('X-PP-AUTHORIZATION', $authSignature);
				} else {
					$httpConfig->addHeader('X-PAYPAL-AUTHORIZATION', $authSignature);
				}
			}
			if($credential instanceof PPSignatureCredential) {
				$handler = new PPSignatureAuthHandler();
			} elseif($credential instanceof PPCertificateCredential) {
				$handler = new PPCertificateAuthHandler();
			} else {
				throw new PPInvalidCredentialException();
			}
			$handler->handle($httpConfig, $request, $options);
		}
	}
