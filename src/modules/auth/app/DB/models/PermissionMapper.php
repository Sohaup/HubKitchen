<?php

namespace PostApi\modules\auth\app\DB\models;

use Error;
use Exception;
use PDO;
use PDOException;
use PostApi\modules\auth\domain\Entities\Permission;

class PermissionMapper
{
    public array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function findAll()
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function findBy(array $critiria)
    {
        $filterPermissionsQuery  = "SELECT * FROM auth.permissions";
        $whereClouses = [];
        $bindings = [];
        if (isset($critiria['id'])) {
            $whereClouses[] = "id = ?";
            $bindings[] = $critiria['id'];
        }
        if (isset($critiria['name'])) {
            $whereClouses[] = "name LIKE ?";
            $bindings[] = "%" . $critiria['name'] . "%";
        }
        if (count($whereClouses) > 0) {
            $filterPermissionsQuery .= " WHERE " . implode(" AND ", $whereClouses);
        }

        try {
            $getPermiisionStmt = $this->db->prepare($filterPermissionsQuery);
            $getPermiisionStmt->execute($bindings);
            $permissions = $getPermiisionStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($permissions as $permissionData) {
                if (!isset($this->identityMap[$permissionData['id']])) {
                    $permission = new Permission();
                    $permission->setId($permissionData['id']);
                    $permission->setName($permissionData['name']);
                    $this->identityMap[$permission->getId()] = $permission;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function insert(Permission $permission)
    {
        try {
            $insertPermissionStmt = $this->db->prepare("INSERT INTO auth.permissions(name) VALUES(?) RETURNING id ");
            $insertPermissionStmt->execute([$permission->getName()]);
            $permissionId = $insertPermissionStmt->fetch(PDO::FETCH_ASSOC)['id'];
            $permission->setId($permissionId);
            $this->identityMap[$permissionId] = $permission;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function update(Permission $permission)
    {
        try {
            if (isset($this->identityMap[$permission->getId()])) {
                $updatePermissionStmt = $this->db->prepare("UPDATE auth.permissions SET name = ? WHERE id = ?");
                $updatePermissionStmt->execute([$permission->getName(), $permission->getId()]);
                $this->identityMap[$permission->getId()] = $permission;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deletePermiisionStmt = $this->db->prepare("DELETE FROM auth.permissions WHERE id = ?");
                $deletePermiisionStmt->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
