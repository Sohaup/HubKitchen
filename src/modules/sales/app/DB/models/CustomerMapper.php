<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Customer;

class CustomerMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getCustomerQuery = $this->db->prepare("SELECT * FROM sales.customers WHERE id = ?");
        $getCustomerQuery->execute([$id]);
        $customerRawData = $getCustomerQuery->fetch(PDO::FETCH_ASSOC);
        if ($customerRawData) {
            $customer = new Customer();
            $customer->setId($customerRawData['id']);
            $customer->setUserId($customerRawData['user_id']);
            $customer->setStripeId($customerRawData['stripe_id']);
            $this->identityMap[$customerRawData['id']] = $customer;
            return $customer;
        }
    }

    public function findAll()
    {
        $getCustomersQuery = $this->db->prepare("SELECT * FROM sales.customers ");
        $getCustomersQuery->execute([]);
        $customersRawData = $getCustomersQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($customersRawData as $customerRawData) {
            if (!isset($this->identityMap[$customerRawData['id']])) {
                $customer = new Customer();
                $customer->setId($customerRawData['id']);
                $customer->setUserId($customerRawData['user_id']);
                $customer->setStripeId($customerRawData['stripe_id']);
                $this->identityMap[$customerRawData['id']] = $customer;
            }
        }
        return $this->identityMap;
    }

    public function create(Customer $customer)
    {
        $createCustomerQuery = $this->db->prepare("INSERT INTO sales.customers(user_id , stripe_id) VALUES(? , ?) RETURNING id");
        $createCustomerQuery->execute([$customer->getUserId(), $customer->getStripeId() ?? null]);
        $customerId = $createCustomerQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $customer->setId($customerId);
        $this->identityMap[$customer->getId()] = $customer;
    }

    public function update(Customer $customer)
    {
        $updateCustomerQuery = $this->db->prepare("UPDATE sales.customers SET user_id = ? WHERE id = ?");
        $updateCustomerQuery->execute([$customer->getUserId(), $customer->getId()]);
        $this->identityMap[$customer->getId()] = $customer;
    }

    public function delete(string $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteCustomerQuery = $this->db->prepare("DELETE FROM sales.customers WHERE id = ?");
            $deleteCustomerQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
