<?php
	namespace PayPal\Auth\Oauth;
	
	class OAuthDataStore {
		/**
		 * @param $consumer_key
		 *
		 * @return void
		 */
		public function lookup_consumer($consumer_key) {
			// implement me
		}
		
		/**
		 * @param $consumer
		 * @param $token_type
		 * @param $token
		 *
		 * @return void
		 */
		public function lookup_token($consumer, $token_type, $token) {
			// implement me
		}
		
		/**
		 * @param $consumer
		 * @param $token
		 * @param $nonce
		 * @param $timestamp
		 *
		 * @return void
		 */
		public function lookup_nonce($consumer, $token, $nonce, $timestamp) {
			// implement me
		}
		
		/**
		 * @param $consumer
		 * @param $callback
		 *
		 * @return void
		 */
		public function new_request_token($consumer, $callback = NULL) {
			// return a new token attached to this consumer
		}
		
		/**
		 * @param $token
		 * @param $consumer
		 * @param $verifier
		 *
		 * @return void
		 */
		public function new_access_token($token, $consumer, $verifier = NULL) {
			// return a new access token attached to this consumer
			// for the user associated with this token if the request token
			// is authorized
			// should also invalidate the request token
		}
		
	}
