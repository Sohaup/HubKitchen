<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\CustomerLogMapper;
use PostApi\modules\CS\domain\entities\CustomerLog;
use PostApi\shared\templates\DB_Trait;

class CustomerLogRepository
{
    use DB_Trait;
    private CustomerLogMapper $mapper;
    public function __construct()
    {
        $this->initialize();
        $this->mapper = new CustomerLogMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->mapper->findOne($id);
    }
    public function findAll() {
        return $this->mapper->findAll();
    }
    public function create(CustomerLog $log) {
        $this->mapper->insert($log);
    }
    public function update(CustomerLog $log) {
        $this->mapper->update($log);
    }
    public function delete(string $id) {
        $this->mapper->delete($id);
    }
}
