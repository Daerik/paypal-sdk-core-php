<?php
	namespace PayPal\Handler;
	
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPRequest;
	
	interface IPPHandler {
		/**
		 *
		 * @param PPHttpConfig $httpConfig
		 * @param PPRequest    $request
		 * @param              $options
		 */
		public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options);
	}
