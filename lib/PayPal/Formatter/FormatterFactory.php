<?php
	namespace PayPal\Formatter;
	
	use InvalidArgumentException;
	class FormatterFactory {
		/**
		 * @param $bindingType
		 *
		 * @return PPNVPFormatter|PPSOAPFormatter
		 */
		public static function factory($bindingType): PPNVPFormatter|PPSOAPFormatter {
			return match ($bindingType) {
				'NV'    => new PPNVPFormatter(),
				'SOAP'  => new PPSOAPFormatter(),
				default => throw new InvalidArgumentException("Invalid value for bindingType. You passed $bindingType"),
			};
		}
	}
