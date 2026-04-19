<?php

namespace PostApi\modules\auth\app\DB\repositories;

use PostApi\modules\auth\app\DB\models\RoleMapper;
use PostApi\modules\auth\domain\aggregators\Permissions;
use PostApi\modules\auth\domain\Entities\Permission;
use PostApi\modules\auth\domain\Entities\Role;
use PostApi\shared\templates\DB_Trait;

class RoleRepository
{
    private RoleMapper $roleMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->roleMapper = new RoleMapper($this->postgre->pdo);
    }
    /**
     * @return Role
     */
    public function findOne(int $id)
    {
        $role = $this->roleMapper->findOne($id);
        return $role;
    }
    public function findAll()
    {
        $roles = $this->roleMapper->findAll();
        return $roles;
    }
    public function create(Role $role)
    {
        $this->roleMapper->insert($role);
    }
    public function update(Role $role)
    {
        $this->roleMapper->update($role);
    }
    public function delete(int $id)
    {
        $this->roleMapper->delete($id);
    }
    public function grantPermission(Role $role, Permission $permission)
    {
        $this->roleMapper->assignPermission($permission, $role);
    }
    public function revokePermission(Role $role, Permission $permission)
    {
        $this->roleMapper->revokePermission($permission, $role);
    }
    /** @return  Permissions*/
    public function getRolePermissions(Role $role)
    {
        return  $this->roleMapper->getPermissions($role);
    }
}
