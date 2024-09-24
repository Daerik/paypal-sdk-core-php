<?php
	namespace PayPal\Core;
	
	/**
	 * PPConfigManager loads the SDK configuration file and
	 * hands out appropriate config params to other classes
	 */
	class PPConfigManager {
		
		public static array $defaults = array(
			"http.ConnectionTimeOut" => "30",
			"http.TimeOut"           => "60",
			"http.Retry"             => "5",
		);
		
		//default config values
		/**
		 * @var PPConfigManager
		 */
		private static PPConfigManager $instance;
		private array $config;
		
		/** @noinspection PhpUndefinedConstantInspection */

		private function __construct() {
			$configFile = NULL;
			if(defined('PP_CONFIG_PATH')) {
				// if PP_CONFIG_PATH *is set* but not set to a string with length > 0
				// then let's disable ini file loading
				if(is_string(PP_CONFIG_PATH) && strlen(PP_CONFIG_PATH) > 0) {
					$configFile = PP_CONFIG_PATH . DIRECTORY_SEPARATOR . 'sdk_config.ini';
				}
			} else {
				$configFile = implode(DIRECTORY_SEPARATOR,
					array(dirname(__FILE__), "..", "config", "sdk_config.ini"));
			}
			if(!is_null($configFile) && file_exists($configFile)) {
				$this->load($configFile);
			}
		}
		
		// create singleton object for PPConfigManager
		
		/**
		 * @param $fileName
		 *
		 * @return void
		 */
		private function load($fileName): void {
			//Gracefully check for ini file
			$parsedConfig = parse_ini_file($fileName);
			if(!empty($parsedConfig)) {
				$this->config = $parsedConfig;
			} else {
				$this->config = array();
			}
		}
		
		//used to load the file
		
		/**
		 * use  the default configuration if it is not passed in hashmap
		 */
		public static function getConfigWithDefaults($config = NULL): array {
			if(!is_array(PPConfigManager::getInstance()->getConfigHashmap()) && $config == NULL) return PPConfigManager::$defaults;
			return array_merge(PPConfigManager::$defaults,
				($config != NULL) ? $config : PPConfigManager::getInstance()->getConfigHashmap());
		}
		
		/**
		 * returns the config file hashmap
		 *
		 */
		private function getConfigHashmap(): array {
			return $this->config;
		}
		
		/**
		 * @return PPConfigManager
		 */
		public static function getInstance(): PPConfigManager {
			if(!isset(self::$instance)) {
				self::$instance = new PPConfigManager();
			}
			return self::$instance;
		}
		
		/**
		 * simple getter for configuration params
		 * If an exact match for key is not found,
		 * does a "contains" search on the key
		 */
		public function get($searchKey) {
			
			if(array_key_exists($searchKey, $this->config)) {
				return $this->config[$searchKey];
			} else {
				$arr = array();
				foreach($this->config as $k => $v) {
					if(strstr($k, $searchKey)) {
						$arr[$k] = $v;
					}
				}
				
				return $arr;
			}
		}
		
		/**
		 * Utility method for handling account configuration
		 * return config key corresponding to the API userId passed in
		 *
		 * If $userId is null, returns config keys corresponding to
		 * all configured accounts
		 */
		public function getIniPrefix($userId = NULL): array|string {
			
			if($userId == NULL) {
				$arr = array();
				foreach($this->config as $key => $value) {
					$pos = strpos($key, '.');
					if(str_contains($key, "acct")) {
						$arr[] = substr($key, 0, $pos);
					}
				}
				return array_unique($arr);
			} else {
				$iniPrefix = array_search($userId, $this->config);
				$pos       = strpos($iniPrefix, '.');
				return substr($iniPrefix, 0, $pos);
			}
		}
	}
