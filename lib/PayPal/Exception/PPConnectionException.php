<?php
	namespace PayPal\Exception;
	
	use Exception;
	class PPConnectionException
		extends Exception {
		/**
		 * The url that was being connected to when the exception occured
		 *
		 * @var string
		 */
		private string $url;
		
		/**
		 * Any response data that was returned by the server
		 *
		 * @var string
		 */
		private string $data;
		
		/**
		 * @param $url
		 * @param $message
		 * @param $code
		 */
		public function __construct($url, $message, $code = 0) {
			parent::__construct($message, $code);
			$this->url = $url;
		}
		
		/**
		 * @return string
		 */
		public function getData(): string {
			return $this->data;
		}
		
		/**
		 * @param $data
		 *
		 * @return void
		 */
		public function setData($data): void {
			$this->data = $data;
		}
		
		/**
		 * @return string
		 */
		public function getUrl(): string {
			return $this->url;
		}
	}
