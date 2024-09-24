<?php
namespace PayPal\Exception;

use Exception;
class PPMissingCredentialException
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
	
	/**
	 * @return string
	 */
	public function errorMessage()
    {
	    return 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
		       . ': <b>' . $this->getMessage() . '</b>';
    }

}
