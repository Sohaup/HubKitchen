<?php

namespace PostApi\modules\CS\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\CustomerLog;
use PostApi\modules\CS\domain\entities\Customer;

class CustomerLogMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare(
                    "SELECT 
                    cl.*,
                    c.country AS customer_country, c.user_id AS customer_user_id
                FROM cs.customers_log AS cl 
                LEFT JOIN cs.customers AS c ON c.id = cl.customer_id
                WHERE cl.id = ?
                "
                );
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;

                $log = new CustomerLog();
                $log->setId($row['id']);
                $customer = new Customer();
                $customer->setId($row['customer_id']);
                $customer->setCountry($row['customer_country']);
                $customer->setUserId($row['customer_user_id']);
                $log->setCustomer($customer);
                $log->setLogType($row['log_type']);
                $log->setCreatedAt($row['created_at']);

                $this->identityMap[$id] = $log;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
                cl.*,
                c.country AS customer_country, c.user_id AS customer_user_id
            FROM cs.customers_log AS cl 
            LEFT JOIN cs.customers AS c ON c.id = cl.customer_id"
            );
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $log = new CustomerLog();
                    $log->setId($row['id']);
                    $customer = new Customer();
                    $customer->setId($row['customer_id']);
                    $customer->setCountry($row['customer_country']);
                    $customer->setUserId($row['customer_user_id']);
                    $log->setCustomer($customer);
                    $log->setLogType($row['log_type']);
                    $log->setCreatedAt($row['created_at']);

                    $this->identityMap[$row['id']] = $log;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT cl.*, c.country AS customer_country, c.user_id AS customer_user_id FROM cs.customers_log AS cl LEFT JOIN cs.customers AS c ON c.id = cl.customer_id";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "cl.id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['customer_id'])) {
            $whereClauses[] = "cl.customer_id = ?";
            $bindings[] = $criteria['customer_id'];
        }

        if (!empty($criteria['log_type'])) {
            $whereClauses[] = "cl.log_type LIKE ?";
            $bindings[] = "%" . $criteria['log_type'] . "%";
        }

        if (!empty($criteria['created_at'])) {
            $whereClauses[] = "cl.created_at = ?";
            $bindings[] = $criteria['created_at'];
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
                    $log = new CustomerLog();
                    $log->setId($row['id']);
                    $customer = new Customer();
                    $customer->setId($row['customer_id']);
                    $customer->setCountry($row['customer_country']);
                    $customer->setUserId($row['customer_user_id']);
                    $log->setCustomer($customer);
                    $log->setLogType($row['log_type']);
                    $log->setCreatedAt($row['created_at']);

                    $this->identityMap[$row['id']] = $log;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(CustomerLog $log)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO cs.customers_log(customer_id, log_type, created_at) VALUES(? , ? , ?) RETURNING id"
            );
            $stmt->execute([$log->getCustomer()->getId(), $log->getLogType(), $log->getCreatedAt()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $log->setId($id);
            $this->identityMap[$id] = $log;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(CustomerLog $log)
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE cs.customers_log SET customer_id = ? , log_type = ? , created_at = ? WHERE id = ?"
            );
            $stmt->execute([$log->getCustomer()->getId(), $log->getLogType(), $log->getCreatedAt(), $log->getId()]);
            $this->identityMap[$log->getId()] = $log;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cs.customers_log WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
