<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\CustomerMapper;
use PostApi\modules\sales\domain\entities\Customer;

class CustomerUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
     private CustomerMapper $customerMapper;
    public function __construct(private PDO $db) {
        $this->customerMapper = new CustomerMapper($db);
    }

    public function registerNew(Customer &$customer)
    {
        if (!in_array($customer, $this->newObjects, true)) {
            $this->newObjects[] = $customer;
        }
    }
    public function registerDirty(Customer &$customer)
    {
        if (!in_array($customer, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $customer;
        }
    }
    public function registerDeleted(Customer &$customer)
    {
        if (!in_array($customer, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $customer;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->customerMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->customerMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->customerMapper->delete($entity->getId());
            }
            $this->db->commit();
            $this->newObjects = [];
            $this->dirtyObjects = [];
            $this->deletedObjects = [];
        } catch (PDOException $error) {
            $this->db->rollBack();
            echo $error->getMessage();
        }
    }
}
