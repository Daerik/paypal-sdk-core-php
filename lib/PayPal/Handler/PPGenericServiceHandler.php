<?php
	namespace PayPal\Handler;
	
	use PayPal\Common\PPUserAgent;
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPRequest;
	use PayPal\Core\PPUtils;
	
	/**
	 *
	 * Adds non-authentication headers that are common to PayPal's
	 * merchant and platform APIs
	 */
	class PPGenericServiceHandler
		implements IPPHandler {
		
		private string $sdkName;
		private string $sdkVersion;
		
		/**
		 * @param $sdkName
		 * @param $sdkVersion
		 */
		public function __construct($sdkName, $sdkVersion) {
			$this->sdkName    = $sdkName;
			$this->sdkVersion = $sdkVersion;
		}
		
		/**
		 * @param PPHttpConfig $httpConfig
		 * @param PPRequest    $request
		 * @param              $options
		 *
		 * @return void
		 */
		public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options): void {
			$httpConfig->addHeader('X-PAYPAL-REQUEST-DATA-FORMAT', $request->getBindingType());
			$httpConfig->addHeader('X-PAYPAL-RESPONSE-DATA-FORMAT', $request->getBindingType());
			$httpConfig->addHeader('X-PAYPAL-DEVICE-IPADDRESS', PPUtils::getLocalIPAddress());
			$httpConfig->addHeader('X-PAYPAL-REQUEST-SOURCE', $this->getRequestSource());
			if(!array_key_exists("User-Agent", $httpConfig->getHeaders())) {
				$httpConfig->addHeader("User-Agent", PPUserAgent::getValue($this->sdkName, $this->sdkVersion));
			}
			if(isset($options['config']['service.SandboxEmailAddress'])) {
				$httpConfig->addHeader('X-PAYPAL-SANDBOX-EMAIL-ADDRESS', $options['config']['service.SandboxEmailAddress']);
			}
		}
		
		/**
		 * Compute the value that needs to sent for the PAYPAL_REQUEST_SOURCE
		 * parameter when making API calls
		 */
		private function getRequestSource(): string {
			return str_replace(" ", "-", $this->sdkName) . "-" . $this->sdkVersion;
		}
	}
