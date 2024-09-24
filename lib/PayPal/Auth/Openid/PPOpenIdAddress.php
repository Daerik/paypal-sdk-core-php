<?php
namespace PayPal\Auth\Openid;

use PayPal\Common\PPModel;

/**
 * End-User's preferred address.
 */
class PPOpenIdAddress
  extends PPModel
{

    /**
     * Full street address component, which may include house number, street name.
     *
     * @param string $street_address
     */
    public function setStreetAddress(string $street_address): static {
        $this->street_address = $street_address;
        return $this;
    }

    /**
     * Full street address component, which may include house number, street name.
     * @return string
     */
    public function getStreetAddress(): string {
        return $this->street_address;
    }

    /**
     * City or locality component.
     *
     * @param string $locality
     */
    public function setLocality(string $locality): static {
        $this->locality = $locality;
        return $this;
    }

    /**
     * City or locality component.
     * @return string
     */
    public function getLocality(): string {
        return $this->locality;
    }

    /**
     * State, province, prefecture or region component.
     *
     * @param string $region
     */
    public function setRegion(string $region): static {
        $this->region = $region;
        return $this;
    }

    /**
     * State, province, prefecture or region component.
     * @return string
     */
    public function getRegion(): string {
        return $this->region;
    }

    /**
     * Zip code or postal code component.
     *
     * @param string $postal_code
     */
    public function setPostalCode(string $postal_code): static {
        $this->postal_code = $postal_code;
        return $this;
    }

    /**
     * Zip code or postal code component.
     * @return string
     */
    public function getPostalCode(): string {
        return $this->postal_code;
    }

    /**
     * Country name component.
     *
     * @param string $country
     */
    public function setCountry(string $country): static {
        $this->country = $country;
        return $this;
    }

    /**
     * Country name component.
     * @return string
     */
    public function getCountry(): string {
        return $this->country;
    }

}
