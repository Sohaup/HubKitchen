<?php

namespace PostApi\modules\manegers\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Department;
use PostApi\modules\manegers\domain\entities\Maneger;
use PostApi\modules\manegers\domain\entities\Plan;

class PlanMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare(
                "SELECT * FROM manegers.plan_view  WHERE id = ?"
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;

            $maneger = new Maneger();
            $maneger->setId($row['manager_id']);
            $maneger->setRank($row['rank']);
            $maneger->setUserId($row['user_id']);
            $department = new Department();
            $department->setId($row['department_id']);
            $department->setName($row['department_name']);
            $maneger->setDepartment($department);
            $plan = new Plan();
            $plan->setId((int)$row['id']);
            $plan->setType($row['type']);
            $plan->setName($row['name']);
            $plan->setDescription($row['description']);
            $plan->setManeger($maneger);

            $this->identityMap[$id] = $plan;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM manegers.plan_view");         
        
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $maneger = new Maneger();
                $maneger->setId($row['manager_id']);
                $maneger->setRank($row['rank']);
                $maneger->setUserId($row['user_id']);
                $department = new Department();
                $department->setId($row['department_id']);
                $department->setName($row['department_name']);
                $maneger->setDepartment($department);
                $plan = new Plan();
                $plan->setId((int)$row['id']);
                $plan->setType($row['type']);
                $plan->setName($row['name']);
                $plan->setDescription($row['description']);
                $plan->setManeger($maneger);

                $this->identityMap[$row['id']] = $plan;
            }
        }

        return $this->identityMap;
    }

    public function insert(Plan $plan)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO managers.plans(type, name, description, maneger_id) VALUES(? , ? , ? , ?) RETURNING id");
            $stmt->execute([$plan->getType(), $plan->getName(), $plan->getDescription(), $plan->getManeger()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $plan->setId((int)$id);
            $this->identityMap[$id] = $plan;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Plan $plan)
    {
        $stmt = $this->db->prepare("UPDATE manegers.plans SET type = ? , name = ? , description = ? , maneger_id = ? WHERE id = ?");
        $stmt->execute([$plan->getType(), $plan->getName(), $plan->getDescription(), $plan->getManeger()->getId(), $plan->getId()]);
        $this->identityMap[$plan->getId()] = $plan;
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM manegers.plans WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
