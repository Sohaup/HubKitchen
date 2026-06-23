<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Action;

class ActionMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.actions WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $action = new Action();
            $action->setId($row['id']);
            $action->setAction($row['action']);
            $action->setTakedAt($row['taked_at']);
            $this->identityMap[$id] = $action;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.actions");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $action = new Action();
                $action->setId($row['id']);
                $action->setAction($row['action']);
                $action->setTakedAt($row['taked_at']);
                $this->identityMap[$row['id']] = $action;
            }
        }
        return $this->identityMap;
    }

    public function insert(Action $action)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.actions(action) VALUES(?) RETURNING id");
            $stmt->execute([$action->getAction()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $action->setId($id);
            $this->identityMap[$id] = $action;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Action $action)
    {
        $stmt = $this->db->prepare("UPDATE cs.actions SET action = ?  WHERE id = ?");
        $stmt->execute([$action->getAction(), $action->getId()]);
        $this->identityMap[$action->getId()] = $action;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.actions WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
