<?php

namespace PostApi\modules\manegers\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Task;
use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;

class TaskMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM manegers.tasks WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $task = new Task();
            $task->setId((int)$row['id']);
            $task->setName($row['name']);
            $task->setDescription($row['description']);
            $manegerRepo = new ManegerRepository();
            $maneger = $manegerRepo->findOne($row['maneger_id']);
            $task->setManeger($maneger);
            $deptRepo = new DepartmentRepository();
            $dept = $deptRepo->findOne((int)$row['department_id']);
            $task->setDepartment($dept);
            $this->identityMap[$id] = $task;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM manegers.tasks");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $task = new Task();
                $task->setId((int)$row['id']);
                $task->setName($row['name']);
                $task->setDescription($row['description']);
                $manegerRepo = new ManegerRepository();
                $maneger = $manegerRepo->findOne($row['maneger_id']);
                $task->setManeger($maneger);
                $deptRepo = new DepartmentRepository();
                $dept = $deptRepo->findOne((int)$row['department_id']);
                $task->setDepartment($dept);
                $this->identityMap[$row['id']] = $task;
            }
        }
        return $this->identityMap;
    }

    public function insert(Task $task)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO manegers.tasks(name, description, maneger_id, department_id) VALUES(? , ? , ?, ?) RETURNING id");
            $stmt->execute([$task->getName(), $task->getDescription(), $task->getManeger()->getId(), $task->getDepartment()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $task->setId((int)$id);
            $this->identityMap[$id] = $task;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Task $task)
    {
        $stmt = $this->db->prepare("UPDATE manegers.tasks SET name = ? , description = ? , maneger_id = ? , department_id = ? WHERE id = ?");
        $stmt->execute([$task->getName(), $task->getDescription(), $task->getManeger()->getId(), $task->getDepartment()->getId(), $task->getId()]);
        $this->identityMap[$task->getId()] = $task;
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM manegers.tasks WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
