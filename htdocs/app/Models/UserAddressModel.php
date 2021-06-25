<?php

namespace App\Models;

use App\Models\Interfaces\UserAddressModelInterface;
use App\Models\CoreModel;

class UserAddressModel extends CoreModel implements UserAddressModelInterface
{

    private int $addressId;
    private string $zipCode;
    private string $country;
    private string $state;
    private string $city;
    private string $number;
    private string $complement;
    private string $accNumber;

    public function __construct()
    {
        parent::__construct('user_address',
        [
            "id_address",
            "zipcode",
            "country",
            "state",
            "city",
            "street",
            "number",
            "complement",
            "acc_number"
        ]);
    }

    /**
     * Sets address ID
     * @param int $id
     */
    public function setAddressId(int $id) : void
    {
        $this->addressId = $id;
    }


    /**
     * Gets address id
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * Sets the zipcode of the address
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode) : void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * Gets the zipcode of the address
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * Sets the country
     * @param string $country
     */
    public function setCountry(string $country) : void
    {
        $this->country = $country;
    }

    /**
     * Gets the country
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the State
     * @param string $state
     */
    public function setState(string $state) : void
    {
        $this->state = $state;
    }

    /**
     * Gets the State
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Sets the city
     * @param string $city
     */
    public function setCity(string $city) : void
    {
        $this->city = $city;
    }

    /**
     * Gets the city
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the number
     * @param string $number
     */
    public function setNumber(string $number) : void
    {
        $this->number = $number;
    }

    /**
     * Gets the number
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Sets the complement
     * @param string $complement
     */
    public function setComplement(string $complement) : void
    {
        $this->complement = $complement;
    }

    /**
     * Gets the complement
     * @return string
     */
    public function getComplement(): string
    {
        return $this->complement;
    }

    /**
     * Sets account number
     * @param string $accNumber
     */
    public function setAccNumber(string $accNumber) : void
    {
        $this->accNumber = $accNumber;
    }

    /**
     * Gets account number
     * @return string
     */
    public function getAccNumber(): string
    {
        return $this->accNumber;
    }
}