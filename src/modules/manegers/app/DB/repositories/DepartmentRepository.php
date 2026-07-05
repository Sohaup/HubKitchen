<?php
namespace PostApi\modules\manegers\app\DB\repositories;

use PostApi\modules\manegers\app\DB\models\DepartmentMapper;
use PostApi\modules\manegers\domain\entities\Department;
use PostApi\shared\templates\DB_Trait;

class DepartmentRepository
{
    use DB_Trait;
    private DepartmentMapper $departmentMapper;
    public function __construct()
    {
        $this->initialize();
        $this->departmentMapper = new DepartmentMapper($this->postgre->pdo);
    }

    public function findOne(int $id) {
        return $this->departmentMapper->findOne($id);
    }

    public function findAll() {
        return $this->departmentMapper->findAll();
    }

    public function create(Department $department) {
        $this->departmentMapper->insert($department);
    }

    public function update(Department $department) {
        $this->departmentMapper->update($department);
    }

    public function delete(int $id) {
        $this->departmentMapper->delete($id);
    }
}
