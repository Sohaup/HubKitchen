<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\CustomerMapper;
use PostApi\modules\CS\domain\entities\Customer;
use PostApi\shared\templates\DB_Trait;

class CustomerRepository
{
    use DB_Trait;
    private CustomerMapper $customerMapper;
    public function __construct()
    {
        $this->initialize();
        $this->customerMapper = new CustomerMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->customerMapper->findOne($id);
    }

    public function findAll() {
        return $this->customerMapper->findAll();
    }

    public function create(Customer $customer) {
        $this->customerMapper->insert($customer);
    }

    public function update(Customer $customer) {
        $this->customerMapper->update($customer);
    }

    public function delete(string $id) {
        $this->customerMapper->delete($id);
    }
}
