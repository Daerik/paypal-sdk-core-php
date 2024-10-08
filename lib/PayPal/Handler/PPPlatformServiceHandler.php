<?php
	namespace PayPal\Handler;
	
	use PayPal\Core\PPConstants;
	use PayPal\Core\PPCredentialManager;
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPRequest;
	use PayPal\Exception\OAuthException;
	use PayPal\Exception\PPConfigurationException;
	use PayPal\Exception\PPInvalidCredentialException;
	use PayPal\Exception\PPMissingCredentialException;
	
	/**
	 *
	 * Adds non-authentication headers that are specific to
	 * PayPal's platform APIs and determines endpoint to
	 * hit based on configuration parameters.
	 *
	 */
	class PPPlatformServiceHandler
		extends PPGenericServiceHandler {
		
		private ?string $apiUsername;
		
		/**
		 * @param $apiUsername
		 * @param $sdkName
		 * @param $sdkVersion
		 */
		public function __construct($apiUsername, $sdkName, $sdkVersion) {
			parent::__construct($sdkName, $sdkVersion);
			$this->apiUsername = $apiUsername;
		}
		
		/**
		 * @param PPHttpConfig $httpConfig
		 * @param PPRequest    $request
		 * @param              $options
		 *
		 * @throws PPConfigurationException
		 * @throws PPInvalidCredentialException
		 * @throws PPMissingCredentialException
		 * @throws OAuthException
		 */
		public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options): void {
			
			parent::handle($httpConfig, $request, $options);
			
			// $apiUsername is optional, if null the default account in config file is taken
			$credMgr = PPCredentialManager::getInstance($options['config']);
			$request->setCredential(clone($credMgr->getCredentialObject($this->apiUsername)));
			$endpoint   = '';
			$config     = $options['config'];
			$credential = $request->getCredential();
			//TODO: Assuming existence of getApplicationId
			if($credential->getApplicationId() != NULL) {
				$httpConfig->addHeader('X-PAYPAL-APPLICATION-ID', $credential->getApplicationId());
			}
			if(isset($config['port']) && isset($config['service.EndPoint.' . $options['port']])) {
				$endpoint = $config['service.EndPoint.' . $options['port']];
			} // for backward compatibilty (for those who are using old config files with 'service.EndPoint')
			elseif(isset($config['service.EndPoint'])) {
				$endpoint = $config['service.EndPoint'];
			} elseif(isset($config['mode'])) {
				if(strtoupper($config['mode']) == 'SANDBOX') {
					$endpoint = PPConstants::PLATFORM_SANDBOX_ENDPOINT;
				} elseif(strtoupper($config['mode']) == 'LIVE') {
					$endpoint = PPConstants::PLATFORM_LIVE_ENDPOINT;
				} elseif(strtoupper($config['mode']) == 'TLS') {
					$endpoint = PPConstants::PLATFORM_TLS_ENDPOINT;
				}
			} else {
				throw new PPConfigurationException('endpoint Not Set');
			}
			$httpConfig->setUrl($endpoint . $options['serviceName'] . '/' . $options['apiMethod']);
			
			// Call the authentication handler to tack authentication related info
			$handler = new PPAuthenticationHandler();
			$handler->handle($httpConfig, $request, $options);
		}
	}
