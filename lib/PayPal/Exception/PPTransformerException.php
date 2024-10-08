<?php
	namespace PayPal\Exception;
	
	use Exception;
	class PPTransformerException
		extends Exception {
		
		/**
		 * @param $message
		 * @param $code
		 */
		public function __construct($message = NULL, $code = 0) {
			parent::__construct($message, $code);
		}
		
		/**
		 * @return string
		 */
		public function errorMessage(): string {
			return 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
			       . ': <b>' . $this->getMessage() . '</b>';
		}
		
	}
