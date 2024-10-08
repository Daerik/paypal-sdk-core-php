<?php
	namespace PayPal\Core;
	
	use Exception;
	use JetBrains\PhpStorm\NoReturn;
	use PayPal\Auth\PPCertificateCredential;
	use PayPal\Auth\PPSignatureCredential;
	use PayPal\Auth\PPSubjectAuthorization;
	use PayPal\Auth\PPTokenAuthorization;
	use PayPal\Exception\PPInvalidCredentialException;
	use PayPal\Exception\PPMissingCredentialException;
	
	class PPCredentialManager {
		private static PPCredentialManager $instance;
		private array                      $credentialHashmap  = array();
		private ?string                    $defaultAccountName = NULL;
		private array                      $config             = array();
		
		/**
		 * @throws PPMissingCredentialException
		 */
		private function __construct($config) {
			try {
				$this->initCredential($config);
			} catch(Exception $e) {
				$this->credentialHashmap = array();
				throw $e;
			}
		}
		
		/*
		 * Create singleton instance for this class.
		 */
		
		/**
		 * @throws PPMissingCredentialException
		 */
		private function initCredential($config): void {
			$suffix = 1;
			$prefix = "acct";
			if(array_key_exists($prefix, $config)) {
				$credArr = $this->config[$prefix];
			} else {
				$arr = array();
				foreach($config as $k => $v) {
					if(strstr($k, $prefix)) {
						$arr[$k] = $v;
					}
				}
				
				$credArr = $arr;
			}
			
			$arr = array();
			foreach($config as $key => $value) {
				$pos = strpos($key, '.');
				if(str_contains($key, "acct")) {
					$arr[] = substr($key, 0, $pos);
				}
			}
			$arrayPartKeys = array_unique($arr);
			
			if(count($arrayPartKeys) == 0) {
				throw new PPMissingCredentialException("No valid API accounts have been configured");
			}
			
			$key = $prefix . $suffix;
			while(in_array($key, $arrayPartKeys)) {
				
				if(isset($credArr[$key . ".Signature"])
				   && $credArr[$key . ".Signature"] != NULL && $credArr[$key . ".Signature"] != ""
				) {
					
					$userName  = $credArr[$key . '.UserName'] ?? "";
					$password  = $credArr[$key . '.Password'] ?? "";
					$signature = $credArr[$key . '.Signature'] ?? "";
					
					$this->credentialHashmap[$userName] = new PPSignatureCredential($userName, $password, $signature);
					if(isset($credArr[$key . '.AppId'])) {
						$this->credentialHashmap[$userName]->setApplicationId($credArr[$key . '.AppId']);
					}
				} elseif(isset($credArr[$key . ".CertPath"])
				         && $credArr[$key . ".CertPath"] != NULL && $credArr[$key . ".CertPath"] != ""
				) {
					
					$userName       = $credArr[$key . '.UserName'] ?? "";
					$password       = $credArr[$key . '.Password'] ?? "";
					$certPassPhrase = $credArr[$key . '.CertKey'] ?? "";
					$certPath       = $credArr[$key . '.CertPath'] ?? "";
					
					$this->credentialHashmap[$userName] = new PPCertificateCredential($userName, $password, $certPath,
						$certPassPhrase);
					if(isset($credArr[$key . '.AppId'])) {
						$this->credentialHashmap[$userName]->setApplicationId($credArr[$key . '.AppId']);
					}
				} elseif(isset($credArr[$key . ".ClientId"])) {
					$userName                           = $key;
					$this->credentialHashmap[$userName] = array(
						'clientId'     => $credArr[$key . ".ClientId"],
						'clientSecret' => $credArr[$key . ".ClientSecret"]
					);
				}
				
				if(!empty($userName) && isset($credArr[$key . ".Subject"]) && trim($credArr[$key . ".Subject"]) != "") {
					$this->credentialHashmap[$userName]->setThirdPartyAuthorization(
						new PPSubjectAuthorization($credArr[$key . ".Subject"]));
				} elseif(!empty($userName) && (isset($credArr[$key . '.accessToken']) && isset($credArr[$key . '.tokenSecret']))) {
					$this->credentialHashmap[$userName]->setThirdPartyAuthorization(
						new PPTokenAuthorization($credArr[$key . '.accessToken'], $credArr[$key . '.tokenSecret']));
				}
				
				if(!empty($userName) && $this->defaultAccountName == NULL) {
					if(array_key_exists($key . '.UserName', $credArr)) {
						$this->defaultAccountName = $credArr[$key . '.UserName'];
					} else {
						$this->defaultAccountName = $key;
					}
				}
				$suffix++;
				$key = $prefix . $suffix;
			}
		}
		
		/*
		 * Load credentials for multiple accounts, with priority given to Signature credential.
		 */
		
		/**
		 * @param $config
		 *
		 * @return PPCredentialManager
		 * @throws PPMissingCredentialException
		 */
		public static function getInstance($config): PPCredentialManager {
			
			return self::$instance = new PPCredentialManager($config);
		}
		
		/*
		 * Obtain Credential Object based on UserId provided.
		 */
		
		/**
		 * @throws PPInvalidCredentialException
		 */
		public function getCredentialObject($userId = NULL) {
			
			if($userId == NULL) {
				$credObj = $this->credentialHashmap[$this->defaultAccountName];
			} elseif(array_key_exists($userId, $this->credentialHashmap)) {
				$credObj = $this->credentialHashmap[$userId];
			}
			
			if(empty($credObj)) {
				throw new PPInvalidCredentialException("Invalid userId $userId");
			}
			return $credObj;
		}
		
		/**
		 * @return void
		 */
		#[NoReturn] public function __clone() {
			trigger_error('Clone is not allowed.', E_USER_ERROR);
		}
		
		/**
		 * @param mixed $config
		 */
		public function setConfig(mixed $config): void {
			$this->config = $config;
		}
		
	}
