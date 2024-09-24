<?php
	namespace PayPal\Common;
	
	class PPUserAgent {
		
		/**
		 * Returns the value of the User-Agent header
		 * Add environment values and php version numbers
		 *
		 * @param string $sdkName
		 * @param string $sdkVersion
		 *
		 * @return string
		 */
		public static function getValue(string $sdkName, string $sdkVersion): string {
			
			$featureList = array(
				'lang=PHP',
				'v=' . PHP_VERSION,
				'bit=' . self::_getPHPBit(),
				'os=' . str_replace(' ', '_', php_uname('s') . ' ' . php_uname('r')),
				'machine=' . php_uname('m')
			);
			if(defined('OPENSSL_VERSION_TEXT')) {
				$opensslVersion = explode(' ', OPENSSL_VERSION_TEXT);
				$featureList[]  = 'openssl=' . $opensslVersion[1];
			}
			if(function_exists('curl_version')) {
				$curlVersion   = curl_version();
				$featureList[] = 'curl=' . $curlVersion['version'];
			}
			return sprintf("PayPalSDK/%s %s (%s)", $sdkName, $sdkVersion, implode(';', $featureList));
		}
		
		/**
		 * @return int|string
		 */
		private static function _getPHPBit(): int|string {
			return match (PHP_INT_SIZE) {
				4       => '32',
				8       => '64',
				default => PHP_INT_SIZE,
			};
		}
	}
