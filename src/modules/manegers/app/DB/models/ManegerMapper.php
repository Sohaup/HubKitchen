<?php

namespace PostApi\modules\manegers\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Department;
use PostApi\modules\manegers\domain\entities\Maneger;

class ManegerMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT m.*, d.name AS department_name FROM manegers.manegers m JOIN manegers.departments d ON m.department_id = d.id WHERE m.id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) return null;

                $manager = new Maneger();
                $manager->setId($row['id']);
                $manager->setRank((int)$row['rank']);
                $manager->setUserId($row['user_id']);

                $department = new Department();
                $department->setId((int)$row['department_id']);
                $department->setName($row['department_name']);
                $manager->setDepartment($department);

                $this->identityMap[$id] = $manager;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT m.*, d.name AS department_name FROM manegers.manegers m JOIN manegers.departments d ON m.department_id = d.id ");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $manager = new Maneger();
                    $manager->setId($row['id']);
                    $manager->setRank((int)$row['rank']);
                    $manager->setUserId($row['user_id']);

                    $department = new Department();
                    $department->setId((int)$row['department_id']);
                    $department->setName($row['department_name']);
                    $manager->setDepartment($department);

                    $this->identityMap[$row['id']] = $manager;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Maneger $maneger)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO manegers.managers(user_id, rank, department_id) VALUES(? , ? , ?) RETURNING id");
            $stmt->execute([$maneger->getUserId(), $maneger->getRank(), $maneger->getDepartment()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $maneger->setId($id);
            $this->identityMap[$id] = $maneger;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Maneger $maneger)
    {
        try {
            $stmt = $this->db->prepare("UPDATE managers.managers SET user_id = ? , rank = ? , department_id = ? WHERE id = ?");
            $stmt->execute([$maneger->getUserId(), $maneger->getRank(), $maneger->getDepartment()->getId(), $maneger->getId()]);
            $this->identityMap[$maneger->getId()] = $maneger;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM manegers.managers WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
