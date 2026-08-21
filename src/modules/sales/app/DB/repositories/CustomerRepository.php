<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\CustomerMapper;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\shared\templates\DB_Trait;

class CustomerRepository
{
    private CustomerMapper $customerMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->customerMapper = new CustomerMapper($this->dataBase);
    }

    public function findOne(string $id)
    {
        return $this->customerMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->customerMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->customerMapper->findBy($critiria);
    }

    public function create(Customer $customer)
    {
        $this->customerMapper->create($customer);
    }

    public function update(Customer $customer)
    {
        $this->customerMapper->update($customer);
    }

    public function delete(string $id)
    {
        $this->customerMapper->delete($id);
    }
}
