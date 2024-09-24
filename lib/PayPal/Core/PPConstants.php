<?php
namespace PayPal\Core;

class PPConstants
{
	public const string SDK_NAME = 'sdk-core-php';
	public const string SDK_VERSION = '3.4.0';
	
	public const string MERCHANT_SANDBOX_SIGNATURE_ENDPOINT = "https://api-3t.sandbox.paypal.com/2.0";
	public const string MERCHANT_SANDBOX_CERT_ENDPOINT      = "https://api.sandbox.paypal.com/2.0";
	public const string PLATFORM_SANDBOX_ENDPOINT      = "https://svcs.sandbox.paypal.com/";
	public const string REST_SANDBOX_ENDPOINT     = "https://api.sandbox.paypal.com/";
	public const string IPN_SANDBOX_ENDPOINT  = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	public const string OPENID_REDIRECT_SANDBOX_URL = "https://www.sandbox.paypal.com";
	
	public const string MERCHANT_LIVE_SIGNATURE_ENDPOINT = "https://api-3t.paypal.com/2.0";
	public const string MERCHANT_LIVE_CERT_ENDPOINT      = "https://api.paypal.com/2.0";
	public const string PLATFORM_LIVE_ENDPOINT      = "https://svcs.paypal.com/";
	public const string REST_LIVE_ENDPOINT     = "https://api.paypal.com/";
	public const string IPN_LIVE_ENDPOINT  = "https://www.paypal.com/cgi-bin/webscr";
	public const string OPENID_REDIRECT_LIVE_URL = "https://www.paypal.com";
	
	public const string MERCHANT_TLS_SIGNATURE_ENDPOINT = "https://test-api-3t.sandbox.paypal.com/2.0";
	public const string MERCHANT_TLS_CERT_ENDPOINT      = "https://test-api.sandbox.paypal.com/2.0";
	public const string PLATFORM_TLS_ENDPOINT      = "https://test-svcs.sandbox.paypal.com/";
	public const string REST_TLS_ENDPOINT     = "https://test-api.sandbox.paypal.com/";
	public const string IPN_TLS_ENDPOINT  = "https://www.test-sandbox.paypal.com/cgi-bin/webscr";
	public const string OPENID_REDIRECT_TLS_URL = "https://www.test-sandbox.paypal.com";
}
