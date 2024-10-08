<?php
	namespace PayPal\Transport;
	
	use PayPal\Common\PPApiContext;
	use PayPal\Core\PPHttpConfig;
	use PayPal\Core\PPHttpConnection;
	use PayPal\Core\PPLoggingManager;
	use PayPal\Exception\PPConfigurationException;
	use PayPal\Exception\PPConnectionException;
	
	class PPRestCall {
		
		/**
		 *
		 * @var PPLoggingManager logger interface
		 */
		private PPLoggingManager $logger;
		
		private PPApiContext $apiContext;
		
		/**
		 * @param $apiContext
		 */
		public function __construct($apiContext) {
			$this->apiContext = $apiContext;
			$this->logger     = new PPLoggingManager(__CLASS__, $apiContext->getConfig());
		}
		
		/**
		 * @param array  $handlers array of handlers
		 * @param string $path     Resource path relative to base service endpoint
		 * @param string $method   HTTP method - one of GET, POST, PUT, DELETE, PATCH etc
		 * @param string $data     Request payload
		 * @param array  $headers  HTTP headers
		 *
		 * @return bool|string
		 * @throws PPConfigurationException
		 * @throws PPConnectionException
		 */
		public function execute(array $handlers, string $path, string $method, string $data = '', array $headers = array()): bool|string {
			
			$config     = $this->apiContext->getConfig();
			$httpConfig = new PPHttpConfig(NULL, $method);
			$httpConfig->setHeaders($headers +
			                        array(
				                        'Content-Type' => 'application/json'
			                        )
			);
			
			foreach($handlers as $handler) {
				if(!is_object($handler)) {
					$shandler = "\\" . $handler;
					$handler  = new $shandler($this->apiContext);
				}
				$handler->handle($httpConfig, $data, array('path' => $path, 'apiContext' => $this->apiContext));
			}
			$connection = new PPHttpConnection($httpConfig, $config);
			$response   = $connection->execute($data);
			$this->logger->fine($response);
			
			return $response;
		}
		
	}
