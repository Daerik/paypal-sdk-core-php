<?php
namespace PayPal\Exception;

use Exception;
class PPTransformerException
  extends Exception
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function errorMessage()
    {
	    return 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
		       . ': <b>' . $this->getMessage() . '</b>';
    }

}
