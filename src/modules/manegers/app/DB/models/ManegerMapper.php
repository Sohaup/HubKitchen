<?php

namespace PostApi\modules\manegers\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\manegers\domain\entities\Maneger;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;

class ManegerMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM manegers.manegers WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $maneger = new Maneger();
            $maneger->setId($row['id']);
            $maneger->setRank((int)$row['rank']);
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($row['user_id']);
            $maneger->setUser($user);
            $deptRepo = new DepartmentRepository();
            $dept = $deptRepo->findOne((int)$row['department_id']);
            $maneger->setDepartment($dept);
            $this->identityMap[$id] = $maneger;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM manegers.manegers");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $maneger = new Maneger();
                $maneger->setId($row['id']);
                $maneger->setRank((int)$row['rank']);
                $userRepo = new UserRepository();
                $user = $userRepo->findOne($row['user_id']);
                $maneger->setUser($user);
                $deptRepo = new DepartmentRepository();
                $dept = $deptRepo->findOne((int)$row['department_id']);
                $maneger->setDepartment($dept);
                $this->identityMap[$row['id']] = $maneger;
            }
        }
        return $this->identityMap;
    }

    public function insert(Maneger $maneger)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO manegers.manegers(user_id, rank, department_id) VALUES(? , ? , ?) RETURNING id");
            $stmt->execute([$maneger->getUser()->getId(), $maneger->getRank(), $maneger->getDepartment()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $maneger->setId($id);
            $this->identityMap[$id] = $maneger;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Maneger $maneger)
    {
        $stmt = $this->db->prepare("UPDATE manegers.manegers SET user_id = ? , rank = ? , department_id = ? WHERE id = ?");
        $stmt->execute([$maneger->getUser()->getId(), $maneger->getRank(), $maneger->getDepartment()->getId(), $maneger->getId()]);
        $this->identityMap[$maneger->getId()] = $maneger;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM manegers.manegers WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
