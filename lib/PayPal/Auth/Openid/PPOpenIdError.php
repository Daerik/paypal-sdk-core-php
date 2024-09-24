<?php
namespace PayPal\Auth\Openid;

use PayPal\Common\PPModel;

/**
 * Error resource
 */
class PPOpenIdError
  extends PPModel
{

    /**
     * A single ASCII error code from the following enum.
     *
     * @param string $error
     */
    public function setError(string $error): static {
        $this->error = $error;
        return $this;
    }

    /**
     * A single ASCII error code from the following enum.
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }

    /**
     * A resource ID that indicates the starting resource in the returned results.
     *
     * @param string $error_description
     */
    public function setErrorDescription(string $error_description): static {
        $this->error_description = $error_description;
        return $this;
    }

    /**
     * A resource ID that indicates the starting resource in the returned results.
     * @return string
     */
    public function getErrorDescription(): string {
        return $this->error_description;
    }

    /**
     * A URI identifying a human-readable web page with information about the error, used to provide the client developer with additional information about the error.
     *
     * @param string $error_uri
     */
    public function setErrorUri(string $error_uri): static {
        $this->error_uri = $error_uri;
        return $this;
    }

    /**
     * A URI identifying a human-readable web page with information about the error, used to provide the client developer with additional information about the error.
     * @return string
     */
    public function getErrorUri(): string {
        return $this->error_uri;
    }

}
