<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\DepartmentMapper;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\shared\templates\DB_Trait;

class DepartmentRepository {
    private DepartmentMapper $departmentMapper;
    use DB_Trait;
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
        $this->departmentMapper->create($department);
    }

    public function update(Department $department) {
        $this->departmentMapper->update($department);
    }

    public function delete(int $id) {
        $this->departmentMapper->delete($id);
    }
}