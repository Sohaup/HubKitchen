<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\EmployeeMapper;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\shared\templates\DB_Trait;

class EmployeeRepository
{
    use DB_Trait;
    private EmployeeMapper $mapper;
    public function __construct()
    {
        $this->initialize();
        $this->mapper = new EmployeeMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->mapper->findOne($id);
    }
    public function findAll() {
        return $this->mapper->findAll();
    }
    public function create(Employee $employee) {
        $this->mapper->insert($employee);
    }
    public function update(Employee $employee) {
        $this->mapper->update($employee);
    }
    public function delete(string $id) {
        $this->mapper->delete($id);
    }
}
