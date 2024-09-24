<?php
	namespace PayPal\Common;
	
	class PPArrayUtil {
		
		/**
		 *
		 * @param array $arr
		 *
		 * @return true if $arr is an associative array
		 */
		public static function isAssocArray(array $arr): bool {
			foreach($arr as $k => $v) {
				if(is_int($k)) {
					return FALSE;
				}
			}
			return TRUE;
		}
	}
