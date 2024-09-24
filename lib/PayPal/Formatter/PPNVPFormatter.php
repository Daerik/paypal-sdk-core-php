<?php
	namespace PayPal\Formatter;
	
	use BadMethodCallException;
	use PayPal\Core\PPRequest;
	class PPNVPFormatter
		implements IPPFormatter {
		
		/**
		 * @param PPRequest $request
		 * @param array     $options
		 *
		 * @return mixed
		 */
		public function toString(PPRequest $request, array $options = array()): mixed {
			return $request->getRequestObject()->toNVPString();
		}
		
		/**
		 * @param       $string
		 * @param array $options
		 *
		 * @return mixed
		 */
		public function toObject($string, array $options = array()): mixed {
			throw new BadMethodCallException("Unimplemented");
		}
	}
