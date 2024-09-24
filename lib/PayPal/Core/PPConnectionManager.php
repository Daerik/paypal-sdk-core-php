<?php
	namespace PayPal\Core;
	
	use PayPal\Exception\PPConfigurationException;
	class PPConnectionManager {
		/**
		 * reference to singleton instance
		 *
		 * @var PPConnectionManager
		 */
		private static PPConnectionManager $instance;
		
		private function __construct() {}
		
		/**
		 * @return PPConnectionManager
		 */
		public static function getInstance(): PPConnectionManager {
			if(self::$instance == NULL) {
				self::$instance = new PPConnectionManager();
			}
			return self::$instance;
		}
		
		/**
		 * This function returns a new PPHttpConnection object
		 *
		 * @throws PPConfigurationException
		 */
		public function getConnection($httpConfig, $config): PPHttpConnection {
			if(isset($config["http.ConnectionTimeOut"])) {
				$httpConfig->setHttpConnectionTimeout($config["http.ConnectionTimeOut"]);
			}
			if(isset($config["http.TimeOut"])) {
				$httpConfig->setHttpTimeout($config["http.TimeOut"]);
			}
			if(isset($config["http.Proxy"])) {
				$httpConfig->setHttpProxy($config["http.Proxy"]);
			}
			if(isset($config["http.Retry"])) {
				$retry = $config["http.Retry"];
				$httpConfig->setHttpRetryCount($retry);
			}
			
			return new PPHttpConnection($httpConfig, $config);
		}
		
	}
