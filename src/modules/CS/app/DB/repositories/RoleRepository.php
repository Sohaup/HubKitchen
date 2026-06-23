<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\RoleMapper;
use PostApi\modules\CS\domain\entities\Role;
use PostApi\shared\templates\DB_Trait;

class RoleRepository
{
    use DB_Trait;
    private RoleMapper $mapper;
    public function __construct()
    {
        $this->initialize();
        $this->mapper = new RoleMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->mapper->findOne($id);
    }
    public function findAll() {
        return $this->mapper->findAll();
    }
    public function create(Role $role) {
        $this->mapper->insert($role);
    }
    public function update(Role $role) {
        $this->mapper->update($role);
    }
    public function delete(string $id) {
        $this->mapper->delete($id);
    }
}
