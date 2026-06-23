<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Status;

class StatusMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM cs.statuses WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $status = new Status();
            $status->setId($row['id']);
            $status->setStatus($row['status']);
            $status->setIssuedAt($row['issued_at']);
            $this->identityMap[$id] = $status;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.statuses");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $status = new Status();
                $status->setId($row['id']);
                $status->setStatus($row['status']);
                $status->setIssuedAt($row['issued_at']);
                $this->identityMap[$row['id']] = $status;
            }
        }
        return $this->identityMap;
    }

    public function insert(Status $status)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.statuses(status) VALUES(?) RETURNING id");
            $stmt->execute([$status->getStatus()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $status->setId($id);
            $this->identityMap[$id] = $status;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Status $status)
    {
        $stmt = $this->db->prepare("UPDATE cs.statuses SET status = ? WHERE id = ?");
        $stmt->execute([$status->getStatus(), $status->getId()]);
        $this->identityMap[$status->getId()] = $status;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.statuses WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
