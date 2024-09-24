<?php
	namespace PayPal\Core;
	
	use PayPal\Common\PPApiContext;
	use PayPal\Exception\PPConfigurationException;
	use PayPal\Exception\PPConnectionException;
	
	class PPBaseService {
		
		protected mixed $config;
		protected mixed $lastRequest;
		protected mixed $lastResponse;
		private mixed  $serviceName;
		private string $serviceBinding;
		private array  $handlers;
		
		/**
		 * @param       $serviceName
		 * @param       $serviceBinding
		 * @param       $config
		 * @param array $handlers
		 */
		public function __construct($serviceName, $serviceBinding, $config = NULL, array $handlers = array()) {
			$this->serviceName    = $serviceName;
			$this->serviceBinding = $serviceBinding;
			$this->config         = $config;
			$this->handlers       = $handlers;
		}
		
		/**
		 * @return mixed
		 */
		public function getLastRequest(): mixed {
			return $this->lastRequest;
		}
		
		/**
		 * @return mixed
		 */
		public function getLastResponse(): mixed {
			return $this->lastResponse;
		}
		
		/**
		 * @return mixed
		 */
		public function getServiceName(): mixed {
			return $this->serviceName;
		}
		
		/**
		 *
		 * @param              $port
		 * @param string       $method        - API method to call
		 * @param object       $requestObject Request object
		 * @param PPApiContext $apiContext    object containing credential and SOAP headers
		 * @param array        $handlers      Array of Handlers
		 *
		 * @return mixed
		 * @throws PPConnectionException
		 * @throws PPConfigurationException
		 * @throws PPConfigurationException
		 */
		public function call($port, string $method, object $requestObject, PPApiContext $apiContext, array $handlers = array()): mixed {
			
			if(!is_array($handlers)) {
				$handlers = array();
			}
			
			$handlers = array_merge($this->handlers, $handlers);
			
			if($apiContext->getConfig() == NULL) {
				$apiContext->setConfig(PPConfigManager::getConfigWithDefaults($this->config));
			}
			
			$service            = new PPAPIService($port, $this->serviceName,
				$this->serviceBinding, $apiContext, $handlers);
			$ret                = $service->makeRequest($method, new PPRequest($requestObject, $this->serviceBinding));
			$this->lastRequest  = $ret['request'];
			$this->lastResponse = $ret['response'];
			return $this->lastResponse;
		}
	}
