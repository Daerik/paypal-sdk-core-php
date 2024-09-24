<?php //vim: foldmethod=marker
namespace PayPal\Auth\Oauth;

class MockOAuthDataStore
	extends OAuthDataStore {/*{{{*/
	private $consumer;
	private $request_token;
	private $access_token;
	private $nonce;
	
	public function __construct() {/*{{{*/
		$this->consumer      = new OAuthConsumer("key", "secret", NULL);
		$this->request_token = new OAuthToken("requestkey", "requestsecret");
		$this->access_token  = new OAuthToken("accesskey", "accesssecret");
		$this->nonce         = "nonce";
	}/*}}}*/
	
	public function lookup_consumer($consumer_key) {/*{{{*/
		if($consumer_key == $this->consumer->key) {
			return $this->consumer;
		}
		return NULL;
	}/*}}}*/
	
	public function lookup_token($consumer, $token_type, $token) {/*{{{*/
		$token_attrib = $token_type . "_token";
		if($consumer->key == $this->consumer->key
		   && $token == $this->$token_attrib->key
		) {
			return $this->$token_attrib;
		}
		return NULL;
	}/*}}}*/
	
	public function lookup_nonce($consumer, $token, $nonce, $timestamp) {/*{{{*/
		if($consumer->key == $this->consumer->key
		   && (($token && $token->key == $this->request_token->key)
		       || ($token && $token->key == $this->access_token->key))
		   && $nonce == $this->nonce
		) {
			return $this->nonce;
		}
		return NULL;
	}/*}}}*/
	
	public function new_request_token($consumer, $callback = NULL) {/*{{{*/
		if($consumer->key == $this->consumer->key) {
			return $this->request_token;
		}
		return NULL;
	}/*}}}*/
	
	public function new_access_token($token, $consumer, $verifier = NULL) {/*{{{*/
		if($consumer->key == $this->consumer->key
		   && $token->key == $this->request_token->key
		) {
			return $this->access_token;
		}
		return NULL;
	}/*}}}*/
}/*}}}*/
