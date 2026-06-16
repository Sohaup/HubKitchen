<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\EmployeeMapper;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\shared\templates\DB_Trait;

class EmployeeRepository
{
    private EmployeeMapper $employeeMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->employeeMapper = new EmployeeMapper($this->postgre->pdo);
    }
    public function findOne(string $id)
    {
        return $this->employeeMapper->findOne($id);
    }
    public function findAll()
    {
        return $this->employeeMapper->findAll();
    }
    public function create(Employee $employee)
    {
        $this->employeeMapper->create($employee);
    }
    public function update(Employee $employee)
    {
        $this->employeeMapper->update($employee);
    }
    public function delete(string $id)
    {
        $this->employeeMapper->delete($id);
    }
}
