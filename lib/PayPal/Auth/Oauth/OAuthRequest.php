<?php
	namespace PayPal\Auth\Oauth;
	
	use PayPal\Exception\OAuthException;
	
	class OAuthRequest {
		public static string $version    = '1.0';
		public static string $POST_INPUT = 'php://input';
		public array     $parameters;
		// for debug purposes
		public string        $base_string;
		protected string $http_method;
		protected string $http_url;
		
		/**
		 * @param $http_method
		 * @param $http_url
		 * @param $parameters
		 */
		public function __construct($http_method, $http_url, $parameters = NULL) {
			$parameters        = ($parameters) ?: array();
			$parameters        = array_merge(OAuthUtil::parse_parameters(parse_url($http_url, PHP_URL_QUERY)), $parameters);
			$this->parameters  = $parameters;
			$this->http_method = $http_method;
			$this->http_url    = $http_url;
		}
		
		/**
		 * attempt to build up a request from what was passed to the server
		 */
		public static function from_request($http_method = NULL, $http_url = NULL, $parameters = NULL): OAuthRequest {
			$scheme      = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on")
				? 'http'
				: 'https';
			$http_url    = ($http_url) ?: $scheme .
			                              '://' . $_SERVER['HTTP_HOST'] .
			                              ':' .
			                              $_SERVER['SERVER_PORT'] .
			                              $_SERVER['REQUEST_URI'];
			$http_method = ($http_method) ?: $_SERVER['REQUEST_METHOD'];
			
			// We weren't handed any parameters, so let's find the ones relevant to
			// this request.
			// If you run XML-RPC or similar you should use this to provide your own
			// parsed parameter-list
			if(!$parameters) {
				// Find request headers
				$request_headers = OAuthUtil::get_headers();
				
				// Parse the query-string to find GET parameters
				$parameters = OAuthUtil::parse_parameters($_SERVER['QUERY_STRING']);
				
				// It's a POST request of the proper content-type, so parse POST
				// parameters and add those overriding any duplicates from GET
				if($http_method == "POST"
				   && isset($request_headers['Content-Type'])
				   && strstr($request_headers['Content-Type'],
						'application/x-www-form-urlencoded')
				) {
					$post_data  = OAuthUtil::parse_parameters(
						file_get_contents(self::$POST_INPUT)
					);
					$parameters = array_merge($parameters, $post_data);
				}
				
				// We have a Authorization-header with OAuth data. Parse the header
				// and add those overriding any duplicates from GET or POST
				if(isset($request_headers['Authorization']) && str_starts_with($request_headers['Authorization'], 'OAuth ')
				) {
					$header_parameters = OAuthUtil::split_header(
						$request_headers['Authorization']
					);
					$parameters        = array_merge($parameters, $header_parameters);
				}
			}
			
			return new OAuthRequest($http_method, $http_url, $parameters);
		}
		
		/**
		 * pretty much a helper function to set up the request
		 */
		public static function from_consumer_and_token($consumer, $token, $http_method, $http_url, $parameters = NULL): OAuthRequest {
			$parameters = ($parameters) ?: array();
			$defaults   = array(
				"oauth_version"   => OAuthRequest::$version,
				"oauth_nonce"     => OAuthRequest::generate_nonce(),
				"oauth_timestamp" => OAuthRequest::generate_timestamp(),
				
				"oauth_consumer_key" => $consumer->key
			);
			if($token) {
				$defaults['oauth_token'] = $token->key;
			}
			
			$parameters = array_merge($defaults, $parameters);
			ksort($parameters);
			return new OAuthRequest($http_method, $http_url, $parameters);
		}
		
		/**
		 * util function: current nonce
		 */
		private static function generate_nonce(): string {
			$mt   = microtime();
			$rand = mt_rand();
			
			return md5($mt . $rand); // md5s look nicer than numbers
		}
		
		/**
		 * util function: current timestamp
		 */
		private static function generate_timestamp(): int {
			return time();
		}
		
		/**
		 * @param $name
		 *
		 * @return null|mixed
		 */
		public function get_parameter($name): mixed {
			return $this->parameters[$name] ?? NULL;
		}
		
		/**
		 * @return array
		 */
		public function get_parameters(): array {
			return $this->parameters;
		}
		
		/**
		 * @param $name
		 *
		 * @return void
		 */
		public function unset_parameter($name): void {
			unset($this->parameters[$name]);
		}
		
		/**
		 * Returns the base string of this request
		 *
		 * The base string defined as the method, the url
		 * and the parameters (normalized), each urlencoded
		 * and the concated with &.
		 */
		public function get_signature_base_string(): string {
			$parts = array(
				$this->get_normalized_http_method(),
				$this->get_normalized_http_url(),
				$this->get_signable_parameters()
			);
			
			$parts = OAuthUtil::urlencode_rfc3986($parts);
			
			return implode('&', $parts);
		}
		
		/**
		 * just uppercases the http method
		 */
		public function get_normalized_http_method(): string {
			return strtoupper($this->http_method);
		}
		
		/**
		 * parses the url and rebuilds it to be
		 * scheme://host/path
		 */
		public function get_normalized_http_url(): string {
			$parts = parse_url($this->http_url);
			
			$scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
			$port   = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
			$host   = (isset($parts['host'])) ? $parts['host'] : '';
			$path   = (isset($parts['path'])) ? $parts['path'] : '';
			
			if(($scheme == 'https' && $port != '443')
			   || ($scheme == 'http' && $port != '80')
			) {
				$host = "$host:$port";
			}
			return "$scheme://$host$path";
		}
		
		/**
		 * The request parameters, sorted and concatenated into a normalized string.
		 *
		 * @return string
		 */
		public function get_signable_parameters(): string {
			// Grab all parameters
			$params = $this->parameters;
			ksort($params);
			$res = array();
			// Remove oauth_signature if present
			// Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
			if(isset($params['oauth_signature'])) {
				unset($params['oauth_signature']);
			}
			foreach($params as $key => $value) {
				$res[] = $key . "=" . $value;
			}
			
			return implode('&', $res);
			//return OAuthUtil::build_http_query($params);
		}
		
		/**
		 * builds the Authorization: header
		 *
		 * @throws OAuthException
		 */
		public function to_header($realm = NULL): string {
			$first = TRUE;
			if($realm) {
				$out   = 'Authorization: OAuth realm="' . OAuthUtil::urlencode_rfc3986($realm) . '"';
				$first = FALSE;
			} else {
				$out = 'Authorization: OAuth';
			}
			
			foreach($this->parameters as $k => $v) {
				if(!str_starts_with($k, "oauth")) {
					continue;
				}
				if(is_array($v)) {
					throw new OAuthException('Arrays not supported in headers');
				}
				$out   .= ($first) ? ' ' : ',';
				$out   .= OAuthUtil::urlencode_rfc3986($k) .
				          '="' .
				          OAuthUtil::urlencode_rfc3986($v) .
				          '"';
				$first = FALSE;
			}
			return $out;
		}
		
		/**
		 * @return string
		 */
		public function __toString() {
			return $this->to_url();
		}
		
		/**
		 * builds a url usable for a GET request
		 */
		public function to_url(): string {
			$post_data = $this->to_postdata();
			$out       = $this->get_normalized_http_url();
			if($post_data) {
				$out .= '?' . $post_data;
			}
			return $out;
		}
		
		/**
		 * builds the data one would send in a POST request
		 */
		public function to_postdata(): string {
			return OAuthUtil::build_http_query($this->parameters);
		}
		
		/**
		 * @throws OAuthException
		 */
		public function sign_request($signature_method, $consumer, $token): void {
			
			$msg = array();
			if($token->key == NULL) {
				$msg[] = 'Token key';
			}
			if($token->secret == NULL) {
				$msg[] = 'Token secret';
			}
			if($consumer->key == NULL) {
				
				$msg[] = 'Consumer key';
			}
			if($consumer->secret == NULL) {
				
				$msg[] = 'Consumer secret';
			}
			if($this->http_url == NULL) {
				
				$msg[] = 'Endpoint';
			}
			if($this->http_method == NULL) {
				
				$msg[] = 'HTTP method';
			}
			if(count($msg)) {
				throw new OAuthException('Enter valid ' . implode(',', $msg));
			}
			$this->set_parameter(
				"oauth_signature_method",
				$signature_method->get_name(),
				FALSE);
			
			$signature = $this->build_signature($signature_method, $consumer, $token);
			$this->set_parameter("oauth_signature", $signature, FALSE);
		}
		
		/**
		 * @param      $name
		 * @param      $value
		 * @param bool $allow_duplicates
		 *
		 * @return void
		 */
		public function set_parameter($name, $value, bool $allow_duplicates = TRUE): void {
			if($allow_duplicates && isset($this->parameters[$name])) {
				// We have already added parameter(s) with this name, so add to the list
				if(is_scalar($this->parameters[$name])) {
					// This is the first duplicate, so transform scalar (string)
					// into an array so we can add the duplicates
					$this->parameters[$name] = array($this->parameters[$name]);
				}
				
				$this->parameters[$name][] = $value;
			} else {
				$this->parameters[$name] = $value;
			}
		}
		
		/**
		 * @param $signature_method
		 * @param $consumer
		 * @param $token
		 *
		 * @return mixed
		 */
		public function build_signature($signature_method, $consumer, $token): mixed {
			return $signature_method->build_signature($this, $consumer, $token);
		}
	}
