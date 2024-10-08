<?php
	namespace PayPal\Auth;
	
	/**
	 *
	 * Represents token based third party authorization
	 * Token based authorization credentials are obtained using
	 * the Permissions API
	 */
	class PPTokenAuthorization
		implements IPPThirdPartyAuthorization {
		
		/**
		 * Permanent access token that identifies the relationship
		 * between the authorizing user and the API caller.
		 *
		 * @var string
		 */
		private string $accessToken;
		
		/**
		 * The token secret/password that will need to be used when
		 * generating the signature.
		 *
		 * @var string
		 */
		private string $tokenSecret;
		
		/**
		 * @param $accessToken
		 * @param $tokenSecret
		 */
		public function __construct($accessToken, $tokenSecret) {
			$this->accessToken = $accessToken;
			$this->tokenSecret = $tokenSecret;
		}
		
		/**
		 * @return string
		 */
		public function getAccessToken(): string {
			return $this->accessToken;
		}
		
		/**
		 * @return string
		 */
		public function getTokenSecret(): string {
			return $this->tokenSecret;
		}
	}
