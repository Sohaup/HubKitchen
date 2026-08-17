<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Addresse;

class AddresseMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getAddressQuery = $this->db->prepare("SELECT * FROM HR.addresses WHERE id = ?");
            $getAddressQuery->execute([$id]);
            $addressRawData = $getAddressQuery->fetch(PDO::FETCH_ASSOC);
            if ($addressRawData) {
                $addresse = new Addresse();
                $addresse->setId($addressRawData['id']);
                $addresse->setCountry($addressRawData['country']);
                $addresse->setCity($addressRawData['city']);
                $addresse->setStreet($addressRawData['street']);
                $addresse->setFlat($addressRawData['flat']);
                $this->identityMap[$addressRawData['id']];
                return $addresse;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getAddressesQuery = $this->db->prepare("SELECT * FROM HR.addresses ");
            $getAddressesQuery->execute([]);
            $addressesRawData = $getAddressesQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($addressesRawData as $addresseRawData) {
                $addresse = new Addresse();
                $addresse->setId($addresseRawData['id']);
                $addresse->setCountry($addresseRawData['country']);
                $addresse->setCity($addresseRawData['city']);
                $addresse->setFlat($addresseRawData['flat']);
                $addresse->setStreet($addresseRawData['street']);
                if (!isset($this->identityMap[$addresseRawData['id']])) {
                    $this->identityMap[$addresseRawData['id']] = $addresse;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Addresse $addresse)
    {
        try {
            $createAddreseQuery = $this->db->prepare("INSERT INTO HR.addresses(country , city , street , flat ) VALUES(? , ? , ? , ? ) RETURNING id ");
            $createAddreseQuery->execute([$addresse->getCountry(), $addresse->getCity(), $addresse->getStreet(), $addresse->getFlat()]);
            $addreseId = $createAddreseQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $addresse->setId($addreseId);
            $this->identityMap[$addresse->getId()] = $addresse;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Addresse $addresse)
    {
        try {
            $updateAddresseQuery = $this->db->prepare("UPDATE HR.addresses SET country = ? , city = ? , street = ? , flat = ?  WHERE id = ?");
            $updateAddresseQuery->execute([$addresse->getCountry(), $addresse->getCity(), $addresse->getStreet(), $addresse->getFlat(), $addresse->getId()]);
            $this->identityMap[$addresse->getId()] = $addresse;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $deleteAddreseQuery = $this->db->prepare("DELETE FROM HR.addresses WHERE id = ?");
            $deleteAddreseQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
