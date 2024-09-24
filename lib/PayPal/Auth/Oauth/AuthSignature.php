<?php
namespace PayPal\Auth\Oauth;

//PayPal specific modification
//Method to be called for generating signature

use PayPal\Exception\OAuthException;
class AuthSignature
{
	
	/**
	 * @throws OAuthException
	 */
	public function genSign($key, $secret, $token, $tokenSecret, $httpMethod, $endpoint): array {

        $authServer  = new OAuthServer(new MockOAuthDataStore());
        $hmac_method = new OAuthSignatureMethodHmacSha1();
        $authServer->add_signature_method($hmac_method);

        $sig_method   = $hmac_method;
        $authConsumer = new OAuthConsumer($key, $secret, null);
        $authToken    = null;
        $authToken    = new OAuthToken($token, $tokenSecret);

        //$params is the query param array which is required only in the httpMethod is "GET"
        $params = array();
        //TODO: set the Query parameters to $params if httpMethod is "GET"

        $acc_req = OAuthRequest::from_consumer_and_token($authConsumer, $authToken, $httpMethod, $endpoint, $params);
        $acc_req->sign_request($sig_method, $authConsumer, $authToken);
        return OAuthUtil::parseQueryString($acc_req);
    }
	
	/**
	 * @param $key
	 * @param $secret
	 * @param $token
	 * @param $tokenSecret
	 * @param $httpMethod
	 * @param $endpoint
	 *
	 * @return string
	 * @throws OAuthException
	 */
	public static function generateFullAuthString($key, $secret, $token, $tokenSecret, $httpMethod, $endpoint): string {
        $authSignature = new AuthSignature();
        $response      = $authSignature->genSign($key, $secret, $token, $tokenSecret, $httpMethod, $endpoint);
        return "token=" . $token .
        ",signature=" . $response['oauth_signature'] .
        ",timestamp=" . $response['oauth_timestamp'];
    }

}
