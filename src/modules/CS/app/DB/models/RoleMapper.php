<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Role;

class RoleMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.roles WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $role = new Role();
            $role->setId($row['id']);
            $role->setName($row['name']);
            $this->identityMap[$id] = $role;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.roles");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $role = new Role();
                $role->setId($row['id']);
                $role->setName($row['name']);
                $this->identityMap[$row['id']] = $role;
            }
        }
        return $this->identityMap;
    }

    public function insert(Role $role)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.roles(name) VALUES(?) RETURNING id");
            $stmt->execute([$role->getName()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $role->setId($id);
            $this->identityMap[$id] = $role;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Role $role)
    {
        $stmt = $this->db->prepare("UPDATE cs.roles SET name = ? WHERE id = ?");
        $stmt->execute([$role->getName(), $role->getId()]);
        $this->identityMap[$role->getId()] = $role;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.roles WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
