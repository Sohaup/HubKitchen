<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Customer;
use PostApi\modules\auth\app\DB\repositories\UserRepository;

class CustomerMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.customers WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $customer = new Customer();
            $customer->setId($row['id']);
            $customer->setCountry($row['country']);
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($row['user_id']);
            $customer->setUser($user);
            $this->identityMap[$id] = $customer;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.customers");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $customer = new Customer();
                $customer->setId($row['id']);
                $customer->setCountry($row['country']);
                $userRepo = new UserRepository();
                $user = $userRepo->findOne($row['user_id']);
                $customer->setUser($user);
                $this->identityMap[$row['id']] = $customer;
            }
        }
        return $this->identityMap;
    }

    public function insert(Customer $customer)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.customers(user_id, country) VALUES(? , ? ) RETURNING id");
            $stmt->execute([$customer->getUser()->getId(), $customer->getCountry()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $customer->setId($id);
            $this->identityMap[$id] = $customer;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Customer $customer)
    {
        $stmt = $this->db->prepare("UPDATE cs.customers SET user_id = ? , country = ? WHERE id = ?");
        $stmt->execute([$customer->getUser()->getId(), $customer->getCountry(), $customer->getId()]);
        $this->identityMap[$customer->getId()] = $customer;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.customers WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
