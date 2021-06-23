<?php

namespace App\Models\Interfaces;

interface UserAddressModelInterface
{
    public function setAddressId(int $id);

    public function getAddressId() : int;
    
    public function setZipCode(string $zipCode);

    public function getZipCode() : string;

    public function setCountry(string $country);

    public function getCountry() : string;

    public function setState(string $state);

    public function getState() : string;

    public function setCity(string $city);

    public function getCity() : string;

    public function setNumber(string $number);

    public function getNumber() : string;

    public function setComplement(string $complement);

    public function getComplement() : string;
    
}