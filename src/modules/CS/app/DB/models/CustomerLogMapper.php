<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\CustomerLog;
use PostApi\modules\CS\app\DB\repositories\CustomerRepository;

class CustomerLogMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.customers_log WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $log = new CustomerLog();
            $log->setId($row['id']);
            $customerRepo = new CustomerRepository();
            $customer = $customerRepo->findOne($row['customer_id']);
            $log->setCustomer($customer);
            $log->setLogType($row['log_type']);
            $log->setCreatedAt($row['created_at']);
            $this->identityMap[$id] = $log;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.customers_log");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $log = new CustomerLog();
                $log->setId($row['id']);
                $customerRepo = new CustomerRepository();
                $customer = $customerRepo->findOne($row['customer_id']);
                $log->setCustomer($customer);
                $log->setLogType($row['log_type']);
                $log->setCreatedAt($row['created_at']);
                $this->identityMap[$row['id']] = $log;
            }
        }
        return $this->identityMap;
    }

    public function insert(CustomerLog $log)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.customers_log(customer_id, log_type, created_at) VALUES(? , ? , ?) RETURNING id");
            $stmt->execute([$log->getCustomer()->getId(), $log->getLogType(), $log->getCreatedAt()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $log->setId($id);
            $this->identityMap[$id] = $log;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(CustomerLog $log)
    {
        $stmt = $this->db->prepare("UPDATE cs.customers_log SET customer_id = ? , log_type = ? , created_at = ? WHERE id = ?");
        $stmt->execute([$log->getCustomer()->getId(), $log->getLogType(), $log->getCreatedAt(), $log->getId()]);
        $this->identityMap[$log->getId()] = $log;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.customers_log WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
