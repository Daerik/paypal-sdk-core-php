<?php
	namespace PayPal\Auth\Oauth;
	
	/**
	 * A class for implementing a Signature Method
	 * See section 9 ("Signing Requests") in the spec
	 */
	abstract class OAuthSignatureMethod {
		/**
		 * Needs to return the name of the Signature Method (ie HMAC-SHA1)
		 *
		 * @return string
		 */
		abstract public function get_name(): string;
		
		/**
		 * Verifies that a given signature is correct
		 *
		 * @param OAuthRequest  $request
		 * @param OAuthConsumer $consumer
		 * @param OAuthToken    $token
		 * @param string        $signature
		 *
		 * @return bool
		 */
		public function check_signature(OAuthRequest $request, OAuthConsumer $consumer, OAuthToken $token, string $signature): bool {
			$built = $this->build_signature($request, $consumer, $token);
			return $built == $signature;
		}
		
		/**
		 * Build up the signature
		 * NOTE: The output of this function MUST NOT be urlencoded.
		 * the encoding is handled in OAuthRequest when the final
		 * request is serialized
		 *
		 * @param OAuthRequest  $request
		 * @param OAuthConsumer $consumer
		 * @param OAuthToken    $token
		 *
		 * @return string
		 */
		abstract public function build_signature(OAuthRequest $request, OAuthConsumer $consumer, OAuthToken $token): string;
	}
