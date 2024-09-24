<?php
namespace PayPal\Core;

use PayPal\Exception\PPConnectionException;
use PayPal\Formatter\FormatterFactory;

class PPAPIService
{

    public $serviceName;
    public $apiMethod;
    public                   $apiContext;
    private PPLoggingManager $logger;
    private array            $handlers = array();
    private                  $serviceBinding;
    private $port;
	
	/**
	 * @param $port
	 * @param $serviceName
	 * @param $serviceBinding
	 * @param $apiContext
	 * @param array $handlers
	 */
	public function __construct($port, $serviceName, $serviceBinding, $apiContext, array $handlers = array())
    {

        $this->apiContext  = $apiContext;
        $this->serviceName = $serviceName;
        $this->port        = $port;

        $this->logger         = new PPLoggingManager(__CLASS__, $this->apiContext->getConfig());
        $this->handlers       = $handlers;
        $this->serviceBinding = $serviceBinding;
    }
	
	/**
	 * @param $serviceName
	 *
	 * @return void
	 */
	public function setServiceName($serviceName): void {
        $this->serviceName = $serviceName;
    }

    /**
     * Register additional handlers to run before
     * executing this call
     *
     * @param IPPHandler $handler
     */
    public function addHandler(IPPHandler $handler): void {
        $this->handlers[] = $handler;
    }
	
	/**
	 * Execute an api call
	 *
	 * @param string $apiMethod Name of the API operation (such as 'Pay')
	 * @param        $request
	 *
	 * @return array containing request and response
	 * @throws PPConnectionException
	 */
    public function makeRequest(string $apiMethod, $request): array {

        $this->apiMethod = $apiMethod;

        $httpConfig = new PPHttpConfig(null, PPHttpConfig::HTTP_POST);
        if ($this->apiContext->getHttpHeaders() != null) {
            $httpConfig->setHeaders($this->apiContext->getHttpHeaders());
        }
        $this->runHandlers($httpConfig, $request);

        // Serialize request object to a string according to the binding configuration
        $formatter = FormatterFactory::factory($this->serviceBinding);
        $payload   = $formatter->toString($request);

        // Execute HTTP call
        $connection = PPConnectionManager::getInstance()->getConnection($httpConfig, $this->apiContext->getConfig());
        $this->logger->info("Request: $payload");
        $response = $connection->execute($payload);
        $this->logger->info("Response: $response");

        return array('request' => $payload, 'response' => $response);
    }
	
	/**
	 * @param $httpConfig
	 * @param $request
	 *
	 * @return void
	 */
	private function runHandlers($httpConfig, $request): void {

        $options = $this->getOptions();
        foreach ($this->handlers as $handlerClass) {
            $handlerClass->handle($httpConfig, $request, $options);
        }
    }
	
	/**
	 * @return array
	 */
	private function getOptions(): array {
        return array(
          'port'           => $this->port,
          'serviceName'    => $this->serviceName,
          'serviceBinding' => $this->serviceBinding,
          'config'         => $this->apiContext->getConfig(),
          'apiMethod'      => $this->apiMethod,
          'apiContext'     => $this->apiContext
        );
    }
}
