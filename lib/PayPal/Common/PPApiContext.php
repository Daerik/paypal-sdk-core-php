<?php
	namespace PayPal\Common;
	
	use PayPal\Core\PPConfigManager;
	use PayPal\Core\PPXmlMessage;
	
	/**
	 *
	 * Container for Call level parameters such as
	 * SDK configuration
	 */
	class PPApiContext {
		
		/**
		 *
		 * @var array Dynamic SDK configuration
		 */
		protected array $config;
		
		/**
		 * @var PPXmlMessage custom SOAPHeader
		 */
		private PPXmlMessage $SOAPHeader;
		
		private array $httpHeaders;
		
		/**
		 *
		 * @param null|array $config SDK configuration parameters
		 */
		public function __construct(array $config = NULL) {
			$this->config = PPConfigManager::getConfigWithDefaults($config);
		}
		
		/**
		 *
		 * @return array
		 */
		public function getHttpHeaders(): array {
			return $this->httpHeaders;
		}
		
		/**
		 *
		 * @param array $httpHeaders associative array of HTTP headers to attach to request
		 */
		public function setHttpHeaders(array $httpHeaders): static {
			$this->httpHeaders = $httpHeaders;
			return $this;
		}
		
		/**
		 *
		 * @param string $name  header name
		 * @param string $value header value
		 * @param bool   $force if true (default), existing value is overwritten
		 */
		public function addHttpHeader(string $name, string $value, bool $force = TRUE): static {
			if(!$force && array_key_exists($name, $this->httpHeaders)) {
				return $this;
			}
			$this->httpHeaders[$name] = $value;
			return $this;
		}
		
		/**
		 *
		 * @return PPXmlMessage
		 */
		public function getSOAPHeader(): PPXmlMessage {
			return $this->SOAPHeader;
		}
		
		/**
		 *
		 * @param PPXmlMessage $SOAPHeader object to attach to SOAP header
		 */
		public function setSOAPHeader(PPXmlMessage $SOAPHeader): static {
			$this->SOAPHeader = $SOAPHeader;
			return $this;
		}
		
		/**
		 * @param $searchKey
		 *
		 * @return array|false|mixed|string
		 */
		public function get($searchKey): mixed {
			if(!isset($this->config)) {
				return PPConfigManager::getInstance()->get($searchKey);
			} elseif(array_key_exists($searchKey, $this->getConfig())) {
				return $this->config[$searchKey];
			}
			return FALSE;
		}
		
		/**
		 *
		 * @return array
		 */
		public function getConfig(): array {
			return $this->config;
		}
		
		/**
		 *
		 * @param array $config SDK configuration parameters
		 */
		public function setConfig(array $config): static {
			$this->config = PPConfigManager::getConfigWithDefaults($config);
			return $this;
		}
	}
