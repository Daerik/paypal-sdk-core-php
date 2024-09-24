<?php
namespace PayPal\Exception;

use Exception;
class PPConfigurationException
  extends Exception
{
	
	/**
	 * @param $message
	 * @param $code
	 */
	public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
