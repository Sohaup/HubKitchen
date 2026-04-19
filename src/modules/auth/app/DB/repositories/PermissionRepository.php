<?php
namespace PostApi\modules\auth\app\DB\repositories;

use PostApi\modules\auth\app\DB\models\PermissionMapper;
use PostApi\modules\auth\domain\Entities\Permission;
use PostApi\shared\templates\DB_Trait;

class PermissionRepository {
    private PermissionMapper $permissionMapper;
    use DB_Trait;
    public function __construct()
    {
       $this->initialize();
       $this->permissionMapper = new PermissionMapper($this->postgre->pdo);
    }
    public function findOne(int $id) {
       $permission = $this->permissionMapper->findOne($id);
       return $permission;
    }
    public function findAll() {
        $permissions = $this->permissionMapper->findAll();
        return $permissions;
    }
    public function create(Permission $permission) {
        $this->permissionMapper->insert($permission);
    }
    public function update(Permission $permission) {
        $this->permissionMapper->update($permission);
    }
    public function delete(int $id) {
        $this->permissionMapper->delete($id);
    }
}