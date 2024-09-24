<?php //vim: foldmethod=marker
	namespace PayPal\Auth\Oauth;
	
	class MockOAuthDataStore
		extends OAuthDataStore {/*{{{*/
		private OAuthConsumer $consumer;
		private OAuthToken    $request_token;
		private OAuthToken    $access_token;
		private string        $nonce;
		
		public function __construct() {/*{{{*/
			$this->consumer      = new OAuthConsumer("key", "secret", NULL);
			$this->request_token = new OAuthToken("requestkey", "requestsecret");
			$this->access_token  = new OAuthToken("accesskey", "accesssecret");
			$this->nonce         = "nonce";
		}/*}}}*/
		
		/**
		 * @param $consumer_key
		 *
		 * @return null|OAuthConsumer
		 */
		public function lookup_consumer($consumer_key): ?OAuthConsumer {/*{{{*/
			if($consumer_key == $this->consumer->key) {
				return $this->consumer;
			}
			return NULL;
		}/*}}}*/
		
		/**
		 * @param $consumer
		 * @param $token_type
		 * @param $token
		 *
		 * @return null|void
		 */
		public function lookup_token($consumer, $token_type, $token) {/*{{{*/
			$token_attrib = $token_type . "_token";
			if($consumer->key == $this->consumer->key
			   && $token == $this->$token_attrib->key
			) {
				return $this->$token_attrib;
			}
			return NULL;
		}/*}}}*/
		
		/**
		 * @param $consumer
		 * @param $token
		 * @param $nonce
		 * @param $timestamp
		 *
		 * @return null|string
		 */
		public function lookup_nonce($consumer, $token, $nonce, $timestamp): ?string {/*{{{*/
			if($consumer->key == $this->consumer->key
			   && (($token && $token->key == $this->request_token->key)
			       || ($token && $token->key == $this->access_token->key))
			   && $nonce == $this->nonce
			) {
				return $this->nonce;
			}
			return NULL;
		}/*}}}*/
		
		/**
		 * @param $consumer
		 * @param $callback
		 *
		 * @return null|OAuthToken
		 */
		public function new_request_token($consumer, $callback = NULL): ?OAuthToken {/*{{{*/
			if($consumer->key == $this->consumer->key) {
				return $this->request_token;
			}
			return NULL;
		}/*}}}*/
		
		/**
		 * @param $token
		 * @param $consumer
		 * @param $verifier
		 *
		 * @return null|OAuthToken
		 */
		public function new_access_token($token, $consumer, $verifier = NULL): ?OAuthToken {/*{{{*/
			if($consumer->key == $this->consumer->key
			   && $token->key == $this->request_token->key
			) {
				return $this->access_token;
			}
			return NULL;
		}/*}}}*/
	}/*}}}*/
