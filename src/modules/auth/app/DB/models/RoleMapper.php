<?php

namespace PostApi\modules\auth\app\DB\models;

use PDO;
use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\domain\aggregators\Permissions;
use PostApi\modules\auth\domain\Entities\Permission;
use PostApi\modules\auth\domain\Entities\Role;

class RoleMapper
{
    /**
     * @return Role[]
     */
    public array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (!isset($this->identityMap[$id])) {
            $getRoleStmt = $this->db->prepare("SELECT * FROM auth.roles WHERE id = ?");
            $getRoleStmt->execute([$id]);
            $roleData = $getRoleStmt->fetch(PDO::FETCH_ASSOC);
            $role = new Role();
            $role->setId($id);
            $role->setName($roleData['name']);
            $this->identityMap[$id] = $role;
            return $role;
        }
        return $this->identityMap[$id];
    }
    public function findAll()
    {
        $getRolesStmt = $this->db->prepare("SELECT * FROM auth.roles ");
        $getRolesStmt->execute([]);
        $roles = $getRolesStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($roles as $roleData) {
            if (!isset($this->identityMap[$roleData['id']])) {
                $role = new Role();
                $role->setId($roleData['id']);
                $role->setName($roleData['name']);
                $this->identityMap[$role->getId()] = $role;
            }
        }
        return $this->identityMap;
    }
    public function insert(Role $role)
    {
        $insertRoleStmt = $this->db->prepare("INSERT INTO auth.roles(name) VALUES (?) RETURNING id ");
        $insertRoleStmt->execute([$role->getName()]);
        $roleId = $insertRoleStmt->fetch(PDO::FETCH_ASSOC)['id'];
        $role->setId($roleId);
        $this->identityMap[$roleId] = $role;
    }
    public function update(Role $role)
    {
        if (isset($this->identityMap[$role->getId()])) {
            $updateRoleStmt = $this->db->prepare("UPDATE auth.roles SET name = ? WHERE id = ? ");
            $updateRoleStmt->execute([$role->getName(), $role->getId()]);
            $this->identityMap[$role->getId()] = $role;
        }
    }
    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteRoleStmt = $this->db->prepare("DELETE FROM auth.roles WHERE id = ?");
            $deleteRoleStmt->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
    public function assignPermission(Permission $permission, Role $role)
    {
        $assignPermissionStmt = $this->db->prepare("INSERT INTO auth.role_permission(role_id , permission_id) VALUES(? , ? ) ");
        $assignPermissionStmt->execute([$role->getId(), $permission->getId()]);
        $role->addPermission($permission);
    }
    public function revokePermission(Permission $permission, Role $role)
    {
        $revokePermissionStmt = $this->db->prepare("DELETE FROM auth.role_permission WHERE role_id = ? AND permission_id = ?");
        $revokePermissionStmt->execute([$role->getId(), $permission->getId()]);
        $role->removePermission($permission);
    }
    public function getPermissions(Role $role)
    {
        $getRolePermissionsStmt = $this->db->prepare("SELECT * FROM auth.role_permission WHERE role_id = ? ");
        $getRolePermissionsStmt->execute([$role->getId()]);
        $rolePermissionsRow = $getRolePermissionsStmt->fetchAll(PDO::FETCH_ASSOC);
        $rolePermissions = new Permissions();
        $permissionsRepository = new PermissionRepository();
        foreach ($rolePermissionsRow as $rolePermissionRow) {
            $permission = $permissionsRepository->findOne($rolePermissionRow['permission_id']);
            $rolePermissions->addPermission($permission);
        }
        return $rolePermissions;
    }
}
