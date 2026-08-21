<?php

namespace PostApi\modules\CS\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Customer;

class CustomerMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT * FROM cs.customers WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;
                $customer = new Customer();
                $customer->setId($row['id']);
                $customer->setCountry($row['country']);
                $customer->setUserId($row['user_id']);
                $this->identityMap[$id] = $customer;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cs.customers");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $customer = new Customer();
                    $customer->setId($row['id']);
                    $customer->setCountry($row['country']);
                    $customer->setUserId($row['user_id']);
                    $this->identityMap[$row['id']] = $customer;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT * FROM cs.customers";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['user_id'])) {
            $whereClauses[] = "user_id = ?";
            $bindings[] = $criteria['user_id'];
        }

        if (!empty($criteria['country'])) {
            $whereClauses[] = "country LIKE ?";
            $bindings[] = "%" . $criteria['country'] . "%";
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $customer = new Customer();
                    $customer->setId($row['id']);
                    $customer->setCountry($row['country']);
                    $customer->setUserId($row['user_id']);
                    $this->identityMap[$row['id']] = $customer;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Customer $customer)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.customers(user_id, country) VALUES(? , ? ) RETURNING id");
            $stmt->execute([$customer->getUserId(), $customer->getCountry()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $customer->setId($id);
            $this->identityMap[$id] = $customer;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Customer $customer)
    {
        try {
            $stmt = $this->db->prepare("UPDATE cs.customers SET user_id = ? , country = ? WHERE id = ?");
            $stmt->execute([$customer->getUserId(), $customer->getCountry(), $customer->getId()]);
            $this->identityMap[$customer->getId()] = $customer;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cs.customers WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
