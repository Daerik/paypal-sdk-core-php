<?php
	namespace PayPal\Core;
	
	use PayPal\Auth\IPPCredential;
	
	/**
	 * Encapsulates API request information
	 *
	 */
	class PPRequest {
		
		/**
		 * Request Object
		 *
		 * @var object
		 */
		private object $requestObject;
		
		/**
		 * Optional credentials associated with
		 * the request
		 *
		 * @var IPPCredential
		 */
		private IPPCredential $credential;
		
		/**
		 * Transport binding for this request.
		 * Can be NVP, SOAP etc
		 *
		 * @var string
		 */
		private string $bindingType;
		
		/**
		 *
		 * Holder for any binding specific info
		 *
		 * @var array
		 */
		private array $bindingInfo = array();
		
		/**
		 * @param $requestObject
		 * @param $bindingType
		 */
		public function __construct($requestObject, $bindingType) {
			$this->requestObject = $requestObject;
			$this->bindingType   = $bindingType;
		}
		
		/**
		 * @return object
		 */
		public function getRequestObject(): object {
			return $this->requestObject;
		}
		
		/**
		 * @return string
		 */
		public function getBindingType(): string {
			return $this->bindingType;
		}
		
		/**
		 * @param $name
		 *
		 * @return null|array|mixed
		 */
		public function getBindingInfo($name = NULL): mixed {
			if(isset($name)) {
				return array_key_exists($name, $this->bindingInfo) ? $this->bindingInfo[$name] : NULL;
			}
			return $this->bindingInfo;
		}
		
		/**
		 *
		 * @param string $name
		 * @param mixed  $value
		 */
		public function addBindingInfo(string $name, mixed $value): void {
			$this->bindingInfo[$name] = $value;
		}
		
		/**
		 * @return IPPCredential
		 */
		public function getCredential(): IPPCredential {
			return $this->credential;
		}
		
		/**
		 * @param $credential
		 *
		 * @return void
		 */
		public function setCredential($credential): void {
			$this->credential = $credential;
		}
	}
