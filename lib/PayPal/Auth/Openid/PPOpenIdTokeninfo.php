<?php
	namespace PayPal\Auth\Openid;
	
	use PayPal\Common\PPApiContext;
	use PayPal\Common\PPModel;
	use PayPal\Exception\PPConfigurationException;
	use PayPal\Exception\PPConnectionException;
	use PayPal\Handler\PPOpenIdHandler;
	use PayPal\Transport\PPRestCall;
	use ReflectionException;
	
	/**
	 * Token grant resource
	 */
	class PPOpenIdTokeninfo
		extends PPModel {
		private int    $expires_in;
		private string $id_token;
		private string $token_type;
		private string $refresh_token;
		private string $access_token;
		private string $scope;
		
		/**
		 * Creates an Access Token from an Authorization Code.
		 *
		 * @path /v1/identity/openidconnect/tokenservice
		 * @method POST
		 *
		 * @param array $params            (allowed values are client_id, client_secret, grant_type, code and redirect_uri)
		 *                                 (required) client_id from developer portal
		 *                                 (required) client_secret from developer portal
		 *                                 (required) code is Authorization code previously received from the authorization server
		 *                                 (required) redirect_uri Redirection endpoint that must match the one provided during the
		 *                                 authorization request that ended in receiving the authorization code.
		 *                                 (optional) grant_type is the Token grant type. Defaults to authorization_code
		 * @param       $clientId
		 * @param       $clientSecret
		 * @param null  $apiContext        Optional API Context
		 *
		 * @return PPOpenIdTokeninfo
		 * @throws PPConfigurationException
		 * @throws PPConnectionException
		 * @throws ReflectionException
		 */
		public static function createFromAuthorizationCode(array $params, $clientId, $clientSecret, $apiContext = NULL): PPOpenIdTokeninfo {
			static $allowedParams = array('grant_type' => 1, 'code' => 1, 'redirect_uri' => 1);
			if(is_null($apiContext)) {
				$apiContext = new PPApiContext();
			}
			
			if($apiContext->get($clientId) !== FALSE) {
				$clientId = $apiContext->get($clientId);
			}
			
			if($apiContext->get($clientSecret) !== FALSE) {
				$clientSecret = $apiContext->get($clientSecret);
			}
			
			if(!array_key_exists('grant_type', $params)) {
				$params['grant_type'] = 'authorization_code';
			}
			
			$call  = new PPRestCall($apiContext);
			$token = new PPOpenIdTokeninfo();
			$token->fromJson(
				$call->execute(array(new PPOpenIdHandler()),
					"/v1/identity/openidconnect/tokenservice", "POST",
					http_build_query(array_intersect_key($params, $allowedParams)),
					array(
						'Content-Type'  => 'application/x-www-form-urlencoded',
						'Authorization' => 'Basic ' . base64_encode($clientId . ":" . $clientSecret)
					)
				));
			return $token;
		}
		
		/**
		 * OPTIONAL, if identical to the scope requested by the client; otherwise, REQUIRED.
		 *
		 * @return string
		 */
		public function getScope(): string {
			return $this->scope;
		}
		
		/**
		 * OPTIONAL, if identical to the scope requested by the client; otherwise, REQUIRED.
		 *
		 * @param string $scope
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setScope(string $scope): static {
			$this->scope = $scope;
			return $this;
		}
		
		/**
		 * The access token issued by the authorization server.
		 *
		 * @return string
		 */
		public function getAccessToken(): string {
			return $this->access_token;
		}
		
		/**
		 * The access token issued by the authorization server.
		 *
		 * @param string $access_token
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setAccessToken(string $access_token): static {
			$this->access_token = $access_token;
			return $this;
		}
		
		/**
		 * The type of the token issued as described in OAuth2.0 RFC6749 (Section 7.1).  Value is case insensitive.
		 *
		 * @return string
		 */
		public function getTokenType(): string {
			return $this->token_type;
		}
		
		/**
		 * The type of the token issued as described in OAuth2.0 RFC6749 (Section 7.1).  Value is case insensitive.
		 *
		 * @param string $token_type
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setTokenType(string $token_type): static {
			$this->token_type = $token_type;
			return $this;
		}
		
		/**
		 * The id_token is a session token assertion that denotes the user's authentication status
		 *
		 * @return string
		 */
		public function getIdToken(): string {
			return $this->id_token;
		}
		
		/**
		 * The id_token is a session token assertion that denotes the user's authentication status
		 *
		 * @param string $id_token
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setIdToken(string $id_token): static {
			$this->id_token = $id_token;
			return $this;
		}
		
		/**
		 * The lifetime in seconds of the access token.
		 *
		 * @return int
		 */
		public function getExpiresIn(): int {
			return $this->expires_in;
		}
		
		/**
		 * The lifetime in seconds of the access token.
		 *
		 * @param int $expires_in
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setExpiresIn(int $expires_in): static {
			$this->expires_in = $expires_in;
			return $this;
		}
		
		/**
		 * Creates an Access Token from an Refresh Token.
		 *
		 * @path /v1/identity/openidconnect/tokenservice
		 * @method POST
		 *
		 * @param array $params          (allowed values are grant_type and scope)
		 *                               (required) client_id from developer portal
		 *                               (required) client_secret from developer portal
		 *                               (optional) refresh_token refresh token. If one is not passed, refresh token from the current object is used.
		 *                               (optional) grant_type is the Token grant type. Defaults to refresh_token
		 *                               (optional) scope is an array that either the same or a subset of the scope passed to the authorization request
		 * @param null  $apiContext      Optional API Context
		 *
		 * @return PPOpenIdTokeninfo
		 * @throws PPConfigurationException
		 * @throws PPConnectionException
		 * @throws ReflectionException
		 */
		public function createFromRefreshToken(array $params, $apiContext = NULL): static {
			
			static $allowedParams = array('grant_type' => 1, 'refresh_token' => 1, 'scope' => 1);
			if(is_null($apiContext)) {
				$apiContext = new PPApiContext();
			}
			
			if(!array_key_exists('grant_type', $params)) {
				$params['grant_type'] = 'refresh_token';
			}
			if(!array_key_exists('refresh_token', $params)) {
				$params['refresh_token'] = $this->getRefreshToken();
			}
			
			$call = new PPRestCall($apiContext);
			$this->fromJson(
				$call->execute(array(new PPOpenIdHandler()),
					"/v1/identity/openidconnect/tokenservice", "POST",
					http_build_query(array_intersect_key($params, $allowedParams)),
					array(
						'Content-Type'  => 'application/x-www-form-urlencoded',
						'Authorization' => 'Basic ' . base64_encode($params['client_id'] . ":" . $params['client_secret'])
					)
				));
			return $this;
		}
		
		/**
		 * The refresh token, which can be used to obtain new access tokens using the same authorization grant as described in OAuth2.0 RFC6749 in Section 6.
		 *
		 * @return string
		 */
		public function getRefreshToken(): string {
			return $this->refresh_token;
		}
		
		/**
		 * The refresh token, which can be used to obtain new access tokens using the same authorization grant as described in OAuth2.0 RFC6749 in Section 6.
		 *
		 * @param string $refresh_token
		 *
		 * @return PPOpenIdTokeninfo
		 */
		public function setRefreshToken(string $refresh_token): static {
			$this->refresh_token = $refresh_token;
			return $this;
		}
	}
