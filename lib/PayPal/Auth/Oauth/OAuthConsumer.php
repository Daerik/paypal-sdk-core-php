<?php
	namespace PayPal\Auth\Oauth;
	
	class OAuthConsumer {
		public string   $key;
		public string   $secret;
		private ?string $callback_url;
		
		/**
		 * @param $key
		 * @param $secret
		 * @param $callback_url
		 */
		public function __construct($key, $secret, $callback_url = NULL) {
			$this->key          = $key;
			$this->secret       = $secret;
			$this->callback_url = $callback_url;
		}
		
		/**
		 * @return string
		 */
		public function __toString() {
			return "OAuthConsumer[key=$this->key,secret=$this->secret]";
		}
		
		/**
		 * @return null|string
		 */
		public function getCallbackUrl(): ?string {
			return $this->callback_url;
		}
	}
