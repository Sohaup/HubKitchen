<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\domain\entities\Lead;

class LeadMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getLeadQuery = $this->db->prepare("SELECT * FROM sales.leads WHERE id = ?");
            $getLeadQuery->execute([$id]);
            $leadRawData = $getLeadQuery->fetch(PDO::FETCH_ASSOC);
            if ($leadRawData) {
                $lead = new Lead();
                $lead->setId($leadRawData['id']);
                $lead->setUserId($leadRawData['user_id'] ?? "");
                $lead->setCreatedAt($leadRawData['created_at']);
                $this->identityMap[$leadRawData['id']] = $lead;
                return $lead;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getLeadsQuery = $this->db->prepare("SELECT * FROM sales.leads ");
            $getLeadsQuery->execute([]);
            $leadsRawData = $getLeadsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($leadsRawData as $leadRawData) {
                if (!isset($this->identityMap[$leadRawData['id']])) {
                    $lead = new Lead();
                    $lead->setId($leadRawData['id']);
                    $lead->setUserId($leadRawData['user_id'] ?? "");
                    $lead->setCreatedAt($leadRawData['created_at']);
                    $this->identityMap[$leadRawData['id']] = $lead;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = [])
    {
        $query = "SELECT * FROM sales.leads";
        $whereClauses = [];
        $bindings = [];

        if (isset($criteria['user_id'])) {
            $whereClauses[] = "user_id = ?";
            $bindings[] = $criteria['user_id'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $leadsRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($leadsRawData as $leadRawData) {
                $lead = new Lead();
                $lead->setId($leadRawData['id']);
                $lead->setUserId($leadRawData['user_id'] ?? "");
                $lead->setCreatedAt($leadRawData['created_at']);
                $this->identityMap[$leadRawData['id']] = $lead;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Lead $lead)
    {
        try {
            $createLeadQuery = $this->db->prepare("INSERT INTO sales.leads(user_id) VALUES(?) RETURNING id, created_at");
            $createLeadQuery->execute([$lead->getUserId()]);
            $res = $createLeadQuery->fetch(PDO::FETCH_ASSOC);
            $lead->setId($res['id']);
            $lead->setCreatedAt($res['created_at']);
            $this->identityMap[$lead->getId()] = $lead;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Lead $lead)
    {
        try {
            $updateLeadQuery = $this->db->prepare("UPDATE sales.leads SET user_id = ? WHERE id = ?");
            $updateLeadQuery->execute([$lead->getUserId(), $lead->getId()]);
            $this->identityMap[$lead->getId()] = $lead;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteLeadQuery = $this->db->prepare("DELETE FROM sales.leads WHERE id = ?");
                $deleteLeadQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
