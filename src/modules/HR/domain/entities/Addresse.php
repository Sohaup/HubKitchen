<?php

namespace PostApi\modules\HR\domain\entities;

class Addresse
{
    private ?int $id = 0;
    private string $country = "";
    private string $city = "";
    private string $street = "";
    private string $flat = "";

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setCountry(string $country)
    {
        $this->country = $country;
    }
    public function getCountry()
    {
        return $this->country;
    }
    public function setCity(string $city)
    {
        $this->city = $city;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function setStreet(string $street)
    {
        $this->street = $street;
    }
    public function getStreet()
    {
        return $this->street;
    }
    public function setFlat(string $flat)
    {
        $this->flat = $flat;
    }
    public function getFlat()
    {
        return $this->flat;
    }
}
