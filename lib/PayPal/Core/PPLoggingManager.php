<?php
	namespace PayPal\Core;
	
	/**
	 * Simple Logging Manager.
	 * This does an error_log for now
	 * Potential frameworks to use are PEAR logger, log4php from Apache
	 */
	class PPLoggingManager {
		// Default Logging Level
		public const int DEFAULT_LOGGING_LEVEL = 0;
		private string  $loggerName;
		private bool    $isLoggingEnabled;
		private int     $loggingLevel  = self::DEFAULT_LOGGING_LEVEL;
		private ?string $loggerFile    = NULL;
		private ?string $loggerMessage = NULL;
		
		/**
		 * @param $loggerName
		 * @param $config
		 */
		public function __construct($loggerName, $config = NULL) {
			$this->loggerName = $loggerName;
			$config           = PPConfigManager::getConfigWithDefaults($config);
			
			$this->isLoggingEnabled = (array_key_exists('log.LogEnabled', $config) && $config['log.LogEnabled'] == '1');
			
			if($this->isLoggingEnabled) {
				$this->loggerFile   = ($config['log.FileName']) ?: ini_get('error_log');
				$loggingLevel       = strtoupper($config['log.LogLevel']);
				$this->loggingLevel = (defined(__NAMESPACE__ . "\\PPLoggingLevel::$loggingLevel")) ? constant(__NAMESPACE__ . "\\PPLoggingLevel::$loggingLevel") : PPLoggingManager::DEFAULT_LOGGING_LEVEL;
			}
		}
		
		/**
		 *
		 */
		public function __destruct() {
			$this->flush();
		}
		
		/**
		 * @return void
		 */
		public function flush(): void {
			if($this->loggerMessage) {
				error_log($this->loggerMessage, 3, $this->loggerFile);
			}
		}
		
		/**
		 * @param $message
		 *
		 * @return void
		 */
		public function error($message): void {
			$this->log($message, PPLoggingLevel::ERROR);
		}
		
		/**
		 * @param     $message
		 * @param int $level
		 *
		 * @return void
		 */
		private function log($message, int $level = PPLoggingLevel::INFO): void {
			if($this->isLoggingEnabled && ($level <= $this->loggingLevel)) {
				$this->loggerMessage .= $this->loggerName . ": $message\n";
			}
		}
		
		/**
		 * @param $message
		 *
		 * @return void
		 */
		public function warning($message): void {
			$this->log($message, PPLoggingLevel::WARN);
		}
		
		/**
		 * @param $message
		 *
		 * @return void
		 */
		public function info($message): void {
			$this->log($message);
		}
		
		/**
		 * @param $message
		 *
		 * @return void
		 */
		public function fine($message): void {
			$this->log($message, PPLoggingLevel::FINE);
		}
		
	}
