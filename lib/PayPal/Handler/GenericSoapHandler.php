<?php
namespace PayPal\Handler;

use PayPal\Core\PPHttpConfig;
use PayPal\Core\PPRequest;
class GenericSoapHandler
  implements IPPHandler
{

    private $namespace;
	
	/**
	 * @param $namespace
	 */
	public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }
	
	/**
	 * @param PPHttpConfig $httpConfig
	 * @param PPRequest    $request
	 * @param $options
	 *
	 * @return void
	 */
	public function handle(PPHttpConfig $httpConfig, PPRequest $request, $options)
    {

        if (isset($options['apiContext'])) {
            if ($options['apiContext']->getHttpHeaders() != null) {
                $httpConfig->setHeaders($options['apiContext']->getHttpHeaders());
            }
            if ($options['apiContext']->getSOAPHeader() != null) {
                $request->addBindingInfo('securityHeader', $options['apiContext']->getSOAPHeader()->toXMLString());
            }
        }

        if (isset($options['config']['service.EndPoint'])) {
            $httpConfig->setUrl($options['config']['service.EndPoint']);
        }
        if (!array_key_exists('Content-Type', $httpConfig->getHeaders())) {
            $httpConfig->addHeader('Content-Type', 'text/xml');
        }

        $request->addBindingInfo("namespace", $this->namespace);
    }
}
