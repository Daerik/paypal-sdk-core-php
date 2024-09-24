<?php
namespace PayPal\Auth\Oauth;

class OAuthConsumer {
	public $key;
	public $secret;
	
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
}
