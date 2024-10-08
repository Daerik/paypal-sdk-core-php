<?php
	namespace PayPal\Auth;
	
	/**
	 *
	 * Represents the older subject based "third party authorization"
	 * New apps can use the more flexible token based authorization
	 */
	class PPSubjectAuthorization
		implements IPPThirdPartyAuthorization {
		
		/**
		 * Paypal emailid of the party who has granted API rights
		 * to the API caller. Your API username must have been
		 * granted permission by this third-party to make any particular
		 * PayPal API request.
		 *
		 * @var string
		 */
		private string $subject;
		
		/**
		 * @param $subject
		 */
		public function __construct($subject) {
			$this->subject = $subject;
		}
		
		/**
		 * @return string
		 */
		public function getSubject(): string {
			return $this->subject;
		}
	}
