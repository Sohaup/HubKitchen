<?php

namespace PostApi\modules\auth\app\DB\models;

use Exception;
use PDO;
use PostApi\modules\auth\domain\Entities\Permission;

class PermissionMapper
{
    public array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (!isset($this->identityMap[$id])) {
            $getPermiisionStmt =  $this->db->prepare("SELECT * FROM auth.permissions WHERE id = ?");
            $getPermiisionStmt->execute([$id]);
            $permissionData = $getPermiisionStmt->fetch(PDO::FETCH_ASSOC);
            $permission = new Permission();
            if (!$permissionData) {
               throw new Exception("no permission found for this id");
            }
            $permission->setId($permissionData['id']);
            $permission->setName($permissionData['name']);
            $this->identityMap[$permission->getId()] = $permission;
            return $permission;
        }
        return $this->identityMap[$id];
    }
    public function findAll()
    {
        $getPermisionsStmt = $this->db->prepare("SELECT * FROM auth.permissions");
        $getPermisionsStmt->execute([]);
        $permissions = $getPermisionsStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($permissions as $permissionData) {
            if (!isset($this->identityMap[$permissionData['id']])) {
                $permission = new Permission();
                $permission->setId($permissionData['id']);
                $permission->setName($permissionData['name']);
                $this->identityMap[$permission->getId()] = $permission;
            }
        }
        return $this->identityMap;
    }
    public function insert(Permission $permission)
    {
        $insertPermissionStmt = $this->db->prepare("INSERT INTO auth.permissions(name) VALUES(?) RETURNING id ");
        $insertPermissionStmt->execute([$permission->getName()]);
        $permissionId = $insertPermissionStmt->fetch(PDO::FETCH_ASSOC)['id'];
        $permission->setId($permissionId);
        $this->identityMap[$permissionId] = $permission;
    }
    public function update(Permission $permission)
    {
        if (isset($this->identityMap[$permission->getId()])) {
            $updatePermissionStmt = $this->db->prepare("UPDATE auth.permissions SET name = ? WHERE id = ?");
            $updatePermissionStmt->execute([$permission->getName(), $permission->getId()]);
            $this->identityMap[$permission->getId()] = $permission;
        }
    }
    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deletePermiisionStmt = $this->db->prepare("DELETE FROM auth.permissions WHERE id = ?");
            $deletePermiisionStmt->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
