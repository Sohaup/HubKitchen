<?php

namespace PostApi\modules\manegers\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Department;
use PostApi\modules\manegers\domain\entities\Maneger;
use PostApi\modules\manegers\domain\entities\Task;

class TaskMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT * FROM manegers.task_view WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;
                $maneger = new Maneger();
                $maneger->setId($row['manager_id']);
                $maneger->setRank($row['rank']);
                $maneger->setUserId($row['user_id']);
                $manegerDepartment = new Department();
                $manegerDepartment->setId($row['department_maneger_id']);
                $manegerDepartment->setName($row['department_maneger_name']);
                $maneger->setDepartment($manegerDepartment);
                $department = new Department();
                $department->setId((int)$row['department_id']);
                $department->setName($row['department_name']);
                $task = new Task();
                $task->setId((int)$row['id']);
                $task->setName($row['name']);
                $task->setDescription($row['description']);
                $task->setManeger($maneger);
                $task->setDepartment($department);
                $this->identityMap[$id] = $task;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM manegers.task_view ");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $maneger = new Maneger();
                    $maneger->setId($row['manager_id']);
                    $maneger->setRank($row['rank']);
                    $maneger->setUserId($row['user_id']);
                    $manegerDepartment = new Department();
                    $manegerDepartment->setId($row['department_maneger_id']);
                    $manegerDepartment->setName($row['department_maneger_name']);
                    $maneger->setDepartment($manegerDepartment);
                    $department = new Department();
                    $department->setId((int)$row['department_id']);
                    $department->setName($row['department_name']);

                    $task = new Task();
                    $task->setId((int)$row['id']);
                    $task->setName($row['name']);
                    $task->setDescription($row['description']);
                    $task->setManeger($maneger);
                    $task->setDepartment($department);

                    $this->identityMap[$row['id']] = $task;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Task $task)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO managers.tasks(name, description, maneger_id, department_id) VALUES(? , ? , ?, ?) RETURNING id");
            $stmt->execute([$task->getName(), $task->getDescription(), $task->getManeger()->getId(), $task->getDepartment()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $task->setId((int)$id);
            $this->identityMap[$id] = $task;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Task $task)
    {
        try {
            $stmt = $this->db->prepare("UPDATE manegers.tasks SET name = ? , description = ? , maneger_id = ? , department_id = ? WHERE id = ?");
            $stmt->execute([$task->getName(), $task->getDescription(), $task->getManeger()->getId(), $task->getDepartment()->getId(), $task->getId()]);
            $this->identityMap[$task->getId()] = $task;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM manegers.tasks WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
