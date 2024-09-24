<?php
namespace PayPal\Auth;

use PayPal\Exception\PPMissingCredentialException;

/**
 *
 * Client certificate based credentials
 */
class PPCertificateCredential
  extends IPPCredential
{

    /**
     * API username
     * @var string
     */
    protected string $userName;

    /**
     * API password
     * @var string
     */
    protected string $password;

    /**
     * Path to PEM encoded API certificate on local filesystem
     * @var string
     */
    protected string $certificatePath;

    /**
     * Password used to protect the API certificate
     * @var string
     */
    protected ?string $certificatePassPhrase;

    /**
     * Application Id that uniquely identifies an application that uses the
     * Platform APIs - Not required for Express Checkout / MassPay / DCC etc
     * The application Id is issued by PayPal.
     * Test application Ids are available for the sandbox environment
     * @var string
     */
    protected string $applicationId;
	
	/**
	 * Constructs a new certificate credential object
	 *
	 * @param string $userName              API username
	 * @param string $password              API password
	 * @param string $certPath              Path to PEM encoded client certificate file
	 * @param null   $certificatePassPhrase password need to use the certificate
	 *
	 * @throws PPMissingCredentialException
	 */
    public function __construct(string $userName, string $password, string $certPath, $certificatePassPhrase = null)
    {
        $this->userName              = trim($userName);
        $this->password              = trim($password);
        $this->certificatePath       = trim($certPath);
        $this->certificatePassPhrase = $certificatePassPhrase;
        $this->validate();
    }
	
	/**
	 * @throws PPMissingCredentialException
	 */
	public function validate(): void {

        if (empty($this->userName)) {
            throw new PPMissingCredentialException("username cannot be empty");
        }
        if (empty($this->password)) {
            throw new PPMissingCredentialException("password cannot be empty");
        }
        if (empty($this->certificatePath)) {
            throw new PPMissingCredentialException("certificate cannot be empty");
        }
    }
	
	/**
	 * @return string
	 */
	public function getUserName(): string {
        return $this->userName;
    }
	
	/**
	 * @return string
	 */
	public function getPassword(): string {
        return $this->password;
    }
	
	/**
	 * @return false|string
	 */
	public function getCertificatePath(): false|string {
        if (realpath($this->certificatePath)) {
            return realpath($this->certificatePath);
        } elseif (defined('PP_CONFIG_PATH')) {
            return constant('PP_CONFIG_PATH') . DIRECTORY_SEPARATOR . $this->certificatePath;
        } else {
            return realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . $this->certificatePath);
        }
    }
	
	/**
	 * @return null|string
	 */
	public function getCertificatePassPhrase(): ?string {
        return $this->certificatePassPhrase;
    }
	
	/**
	 * @param $applicationId
	 *
	 * @return void
	 */
	public function setApplicationId($applicationId): void {
        $this->applicationId = trim($applicationId);
    }
	
	/**
	 * @return string
	 */
	public function getApplicationId(): string {
        return $this->applicationId;
    }

}
