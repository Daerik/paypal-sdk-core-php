<?php
	namespace PayPal\Common;
	
	use ReflectionException;
	use ReflectionMethod;
	use RuntimeException;
	class PPReflectionUtil {
		
		/**
		 * @var array|ReflectionMethod[]
		 */
		private static array $propertiesRefl = array();
		
		/**
		 * @var array|string[]
		 */
		private static array $propertiesType = array();
		
		/**
		 *
		 * @param string $class
		 * @param string $propertyName
		 *
		 * @return mixed|string
		 * @throws ReflectionException
		 */
		public static function getPropertyClass(string $class, string $propertyName): mixed {
			
			if(($annotations = self::propertyAnnotations($class, $propertyName)) && isset($annotations['return'])) {
				// 			if (substr($annotations['param'], -2) === '[]') {
				// 				$param = substr($annotations['param'], 0, -2);
				// 			}
				$param = $annotations['return'];
			}
			
			if(isset($param)) {
				$anno = explode(' ', $param);
				return $anno[0];
			} else {
				return 'string';
			}
		}
		
		/**
		 * @param string $class
		 * @param string $propertyName
		 *
		 * @return null|array
		 * @throws ReflectionException
		 */
		public static function propertyAnnotations(string $class, string $propertyName): ?array {
			if(!class_exists('ReflectionProperty')) {
				throw new RuntimeException("Property type of " . $class . "::$propertyName cannot be resolved");
			}
			
			if($annotations =& self::$propertiesType[$class][$propertyName]) {
				return $annotations;
			}
			
			if(!($refl =& self::$propertiesRefl[$class][$propertyName])) {
				$getter                                      = method_exists($class,
					"get" . ucfirst($propertyName)) ? "get" . ucfirst($propertyName)
					: "get" . preg_replace_callback("/([_\-\s]?([a-z0-9]+))/", array(self::class, 'replace_callback'), $propertyName);
				$refl                                        = new ReflectionMethod($class, $getter);
				self::$propertiesRefl[$class][$propertyName] = $refl;
			}
			
			// todo: smarter regexp
			if(!preg_match_all('~\@([^\s@\(]+)[\t ]*(?:\(?([^\n@]+)\)?)?~i', $refl->getDocComment(), $annots,
				PREG_PATTERN_ORDER)
			) {
				return NULL;
			}
			foreach($annots[1] as $i => $annot) {
				$annotations[strtolower($annot)] = empty($annots[2][$i]) ? TRUE : rtrim($annots[2][$i], " \t\n\r)");
			}
			
			return $annotations;
		}
		
		/**
		 * preg_replace_callback callback function
		 */
		private static function replace_callback($match): string {
			return ucwords($match[2]);
		}
	}
