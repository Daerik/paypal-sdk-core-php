<?php
	namespace PayPal\Common;
	
	use ReflectionException;
	/**
	 * Generic Model class that all API domain classes extend
	 * Stores all member data in a hashmap that enables easy
	 * JSON encoding/decoding
	 */
	class PPModel {
		
		private array $_propMap = array();
		
		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function __get($key) {
			return $this->_propMap[$key];
		}
		
		/**
		 * @param $key
		 * @param $value
		 *
		 * @return void
		 */
		public function __set($key, $value) {
			$this->_propMap[$key] = $value;
		}
		
		/**
		 * @param $key
		 *
		 * @return bool
		 */
		public function __isset($key) {
			return isset($this->_propMap[$key]);
		}
		
		/**
		 * @param $key
		 *
		 * @return void
		 */
		public function __unset($key) {
			unset($this->_propMap[$key]);
		}
		
		/**
		 * @param $json
		 *
		 * @return void
		 * @throws ReflectionException
		 * @throws ReflectionException
		 */
		public function fromJson($json): void {
			$this->fromArray(json_decode($json, TRUE));
		}
		
		/**
		 * @param $arr
		 *
		 * @return void
		 * @throws ReflectionException
		 * @throws ReflectionException
		 */
		public function fromArray($arr): void {
			
			foreach($arr as $k => $v) {
				if(is_array($v)) {
					$clazz = PPReflectionUtil::getPropertyClass(get_class($this), $k);
					
					if(PPArrayUtil::isAssocArray($v)) {
						$o = new $clazz();
						$o->fromArray($v);
						$this->__set($k, $o);
					} else {
						$arr = array();
						foreach($v as $nk => $nv) {
							if(is_array($nv)) {
								$o = new $clazz();
								$o->fromArray($nv);
								$arr[$nk] = $o;
							} else {
								$arr[$nk] = $nv;
							}
						}
						$this->__set($k, $arr);
					}
				} else {
					$this->$k = $v;
				}
			}
		}
		
		/**
		 * @return false|string
		 */
		public function toJSON(): false|string {
			return json_encode($this->toArray());
		}
		
		/**
		 * @param $param
		 *
		 * @return array
		 */
		private function _convertToArray($param): array {
			$ret = array();
			foreach($param as $k => $v) {
				if($v instanceof PPModel) {
					$ret[$k] = $v->toArray();
				} elseif(is_array($v)) {
					$ret[$k] = $this->_convertToArray($v);
				} else {
					$ret[$k] = $v;
				}
			}
			return $ret;
		}
		
		/**
		 * @return array
		 */
		public function toArray(): array {
			return $this->_convertToArray($this->_propMap);
		}
	}
