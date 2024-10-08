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
	 * OpenIdConnect UserInfo Resource
	 */
	class PPOpenIdUserinfo
		extends PPModel {
		private string          $payer_id;
		private string          $age_range;
		private string          $account_type;
		private bool            $verified_account;
		private PPOpenIdAddress $address;
		private string          $phone_number;
		private bool            $verified;
		private string          $language;
		private string          $locale;
		private string          $zoneinfo;
		private string          $birthday;
		private string          $gender;
		private bool            $email_verified;
		private string          $email;
		private string          $picture;
		private string          $middle_name;
		private string          $family_name;
		private string          $given_name;
		private string          $name;
		private string          $sub;
		private string          $user_id;
		
		/**
		 * returns user details
		 *
		 * @path /v1/identity/openidconnect/userinfo
		 * @method GET
		 *
		 * @param array $params            (allowed values are access_token)
		 *                                 access_token - access token from the createFromAuthorizationCode / createFromRefreshToken calls
		 * @param null  $apiContext        Optional API Context
		 *
		 * @return PPOpenIdUserinfo
		 * @throws PPConfigurationException
		 * @throws PPConnectionException
		 * @throws ReflectionException
		 */
		public static function getUserinfo(array $params, $apiContext = NULL): PPOpenIdUserinfo {
			static $allowedParams = array('schema' => 1);
			if(is_null($apiContext)) {
				$apiContext = new PPApiContext();
			}
			
			if(!array_key_exists('schema', $params)) {
				$params['schema'] = 'openid';
			}
			$requestUrl = "/v1/identity/openidconnect/userinfo?"
			              . http_build_query(array_intersect_key($params, $allowedParams));
			$call       = new PPRestCall($apiContext);
			$ret        = new PPOpenIdUserinfo();
			$ret->fromJson(
				$call->execute(array(new PPOpenIdHandler()), $requestUrl, "GET", "",
					array(
						'Authorization' => "Bearer " . $params['access_token'],
						'Content-Type'  => 'x-www-form-urlencoded'
					)
				)
			);
			return $ret;
		}
		
		/**
		 * Subject - Identifier for the End-User at the Issuer.
		 *
		 * @return string
		 */
		public function getUserId(): string {
			return $this->user_id;
		}
		
		/**
		 * Subject - Identifier for the End-User at the Issuer.
		 *
		 * @param string $user_id
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setUserId(string $user_id): static {
			$this->user_id = $user_id;
			return $this;
		}
		
		/**
		 * Subject - Identifier for the End-User at the Issuer.
		 *
		 * @return string
		 */
		public function getSub(): string {
			return $this->sub;
		}
		
		/**
		 * Subject - Identifier for the End-User at the Issuer.
		 *
		 * @param string $sub
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setSub(string $sub): static {
			$this->sub = $sub;
			return $this;
		}
		
		/**
		 * End-User's full name in displayable form including all name parts, possibly including titles and suffixes, ordered according to the End-User's locale and preferences.
		 *
		 * @return string
		 */
		public function getName(): string {
			return $this->name;
		}
		
		/**
		 * End-User's full name in displayable form including all name parts, possibly including titles and suffixes, ordered according to the End-User's locale and preferences.
		 *
		 * @param string $name
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setName(string $name): static {
			$this->name = $name;
			return $this;
		}
		
		/**
		 * Given name(s) or first name(s) of the End-User
		 *
		 * @return string
		 */
		public function getGivenName(): string {
			return $this->given_name;
		}
		
		/**
		 * Given name(s) or first name(s) of the End-User
		 *
		 * @param string $given_name
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setGivenName(string $given_name): static {
			$this->given_name = $given_name;
			return $this;
		}
		
		/**
		 * Surname(s) or last name(s) of the End-User.
		 *
		 * @return string
		 */
		public function getFamilyName(): string {
			return $this->family_name;
		}
		
		/**
		 * Surname(s) or last name(s) of the End-User.
		 *
		 * @param string $family_name
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setFamilyName(string $family_name): static {
			$this->family_name = $family_name;
			return $this;
		}
		
		/**
		 * Middle name(s) of the End-User.
		 *
		 * @return string
		 */
		public function getMiddleName(): string {
			return $this->middle_name;
		}
		
		/**
		 * Middle name(s) of the End-User.
		 *
		 * @param string $middle_name
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setMiddleName(string $middle_name): static {
			$this->middle_name = $middle_name;
			return $this;
		}
		
		/**
		 * URL of the End-User's profile picture.
		 *
		 * @return string
		 */
		public function getPicture(): string {
			return $this->picture;
		}
		
		/**
		 * URL of the End-User's profile picture.
		 *
		 * @param string $picture
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setPicture(string $picture): static {
			$this->picture = $picture;
			return $this;
		}
		
		/**
		 * End-User's preferred e-mail address.
		 *
		 * @return string
		 */
		public function getEmail(): string {
			return $this->email;
		}
		
		/**
		 * End-User's preferred e-mail address.
		 *
		 * @param string $email
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setEmail(string $email): static {
			$this->email = $email;
			return $this;
		}
		
		/**
		 * True if the End-User's e-mail address has been verified; otherwise false.
		 *
		 * @return bool
		 */
		public function getEmailVerified(): bool {
			return $this->email_verified;
		}
		
		/**
		 * True if the End-User's e-mail address has been verified; otherwise false.
		 *
		 * @param bool $email_verified
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setEmailVerified(bool $email_verified): static {
			$this->email_verified = $email_verified;
			return $this;
		}
		
		/**
		 * End-User's gender.
		 *
		 * @return string
		 */
		public function getGender(): string {
			return $this->gender;
		}
		
		/**
		 * End-User's gender.
		 *
		 * @param string $gender
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setGender(string $gender): static {
			$this->gender = $gender;
			return $this;
		}
		
		/**
		 * End-User's birthday, represented as an YYYY-MM-DD format. They year MAY be 0000, indicating it is omited. To represent only the year, YYYY format would be used.
		 *
		 * @return string
		 */
		public function getBirthday(): string {
			return $this->birthday;
		}
		
		/**
		 * End-User's birthday, represented as an YYYY-MM-DD format. They year MAY be 0000, indicating it is omited. To represent only the year, YYYY format would be used.
		 *
		 * @param string $birthday
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setBirthday(string $birthday): static {
			$this->birthday = $birthday;
			return $this;
		}
		
		/**
		 * Time zone database representing the End-User's time zone
		 *
		 * @return string
		 */
		public function getZoneinfo(): string {
			return $this->zoneinfo;
		}
		
		/**
		 * Time zone database representing the End-User's time zone
		 *
		 * @param string $zoneinfo
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setZoneinfo(string $zoneinfo): static {
			$this->zoneinfo = $zoneinfo;
			return $this;
		}
		
		/**
		 * End-User's locale.
		 *
		 * @return string
		 */
		public function getLocale(): string {
			return $this->locale;
		}
		
		/**
		 * End-User's locale.
		 *
		 * @param string $locale
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setLocale(string $locale): static {
			$this->locale = $locale;
			return $this;
		}
		
		/**
		 * End-User's language.
		 *
		 * @return string
		 */
		public function getLanguage(): string {
			return $this->language;
		}
		
		/**
		 * End-User's language.
		 *
		 * @param string $language
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setLanguage(string $language): static {
			$this->language = $language;
			return $this;
		}
		
		/**
		 * End-User's verified status.
		 *
		 * @return bool
		 */
		public function getVerified(): bool {
			return $this->verified;
		}
		
		/**
		 * End-User's verified status.
		 *
		 * @param bool $verified
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setVerified(bool $verified): static {
			$this->verified = $verified;
			return $this;
		}
		
		/**
		 * End-User's preferred telephone number.
		 *
		 * @return string
		 */
		public function getPhoneNumber(): string {
			return $this->phone_number;
		}
		
		/**
		 * End-User's preferred telephone number.
		 *
		 * @param string $phone_number
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setPhoneNumber(string $phone_number): static {
			$this->phone_number = $phone_number;
			return $this;
		}
		
		/**
		 * End-User's preferred address.
		 *
		 * @return PPOpenIdAddress
		 */
		public function getAddress(): PPOpenIdAddress {
			return $this->address;
		}
		
		/**
		 * End-User's preferred address.
		 *
		 * @param PPOpenIdAddress $address
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setAddress(PPOpenIdAddress $address): static {
			$this->address = $address;
			return $this;
		}
		
		/**
		 * Verified account status.
		 *
		 * @return bool
		 */
		public function getVerifiedAccount(): bool {
			return $this->verified_account;
		}
		
		/**
		 * Verified account status.
		 *
		 * @param bool $verified_account
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setVerifiedAccount(bool $verified_account): static {
			$this->verified_account = $verified_account;
			return $this;
		}
		
		/**
		 * Account type.
		 *
		 * @return string
		 */
		public function getAccountType(): string {
			return $this->account_type;
		}
		
		/**
		 * Account type.
		 *
		 * @param string $account_type
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setAccountType(string $account_type): static {
			$this->account_type = $account_type;
			return $this;
		}
		
		/**
		 * Account holder age range.
		 *
		 * @return string
		 */
		public function getAgeRange(): string {
			return $this->age_range;
		}
		
		/**
		 * Account holder age range.
		 *
		 * @param string $age_range
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setAgeRange(string $age_range): static {
			$this->age_range = $age_range;
			return $this;
		}
		
		/**
		 * Account payer identifier.
		 *
		 * @return string
		 */
		public function getPayerId(): string {
			return $this->payer_id;
		}
		
		/**
		 * Account payer identifier.
		 *
		 * @param string $payer_id
		 *
		 * @return PPOpenIdUserinfo
		 */
		public function setPayerId(string $payer_id): static {
			$this->payer_id = $payer_id;
			return $this;
		}
	}
