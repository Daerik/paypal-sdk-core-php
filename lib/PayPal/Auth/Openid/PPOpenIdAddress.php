<?php
	namespace PayPal\Auth\Openid;
	
	use PayPal\Common\PPModel;
	
	/**
	 * End-User's preferred address.
	 */
	class PPOpenIdAddress
		extends PPModel {
		private string $country;
		private string $postal_code;
		private string $region;
		private string $locality;
		private string $street_address;
		
		/**
		 * Full street address component, which may include house number, street name.
		 *
		 * @return string
		 */
		public function getStreetAddress(): string {
			return $this->street_address;
		}
		
		/**
		 * Full street address component, which may include house number, street name.
		 *
		 * @param string $street_address
		 *
		 * @return PPOpenIdAddress
		 */
		public function setStreetAddress(string $street_address): static {
			$this->street_address = $street_address;
			return $this;
		}
		
		/**
		 * City or locality component.
		 *
		 * @return string
		 */
		public function getLocality(): string {
			return $this->locality;
		}
		
		/**
		 * City or locality component.
		 *
		 * @param string $locality
		 *
		 * @return PPOpenIdAddress
		 */
		public function setLocality(string $locality): static {
			$this->locality = $locality;
			return $this;
		}
		
		/**
		 * State, province, prefecture or region component.
		 *
		 * @return string
		 */
		public function getRegion(): string {
			return $this->region;
		}
		
		/**
		 * State, province, prefecture or region component.
		 *
		 * @param string $region
		 *
		 * @return PPOpenIdAddress
		 */
		public function setRegion(string $region): static {
			$this->region = $region;
			return $this;
		}
		
		/**
		 * Zip code or postal code component.
		 *
		 * @return string
		 */
		public function getPostalCode(): string {
			return $this->postal_code;
		}
		
		/**
		 * Zip code or postal code component.
		 *
		 * @param string $postal_code
		 *
		 * @return PPOpenIdAddress
		 */
		public function setPostalCode(string $postal_code): static {
			$this->postal_code = $postal_code;
			return $this;
		}
		
		/**
		 * Country name component.
		 *
		 * @return string
		 */
		public function getCountry(): string {
			return $this->country;
		}
		
		/**
		 * Country name component.
		 *
		 * @param string $country
		 *
		 * @return PPOpenIdAddress
		 */
		public function setCountry(string $country): static {
			$this->country = $country;
			return $this;
		}
		
	}
