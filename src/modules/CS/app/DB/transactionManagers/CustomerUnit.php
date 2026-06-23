<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\CustomerMapper;
use PostApi\modules\CS\domain\entities\Customer;

class CustomerUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private CustomerMapper $customerMapper, private PDO $db) {}
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
                $this->customerMapper->insert($entity);
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
