<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
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
    }

    public function findAll()
    {
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
    }

    public function create(Lead $lead)
    {
        $createLeadQuery = $this->db->prepare("INSERT INTO sales.leads(user_id) VALUES(?) RETURNING id, created_at");
        $createLeadQuery->execute([$lead->getUserId()]);
        $res = $createLeadQuery->fetch(PDO::FETCH_ASSOC);
        $lead->setId($res['id']);
        $lead->setCreatedAt($res['created_at']);
        $this->identityMap[$lead->getId()] = $lead;
    }

    public function update(Lead $lead)
    {
        $updateLeadQuery = $this->db->prepare("UPDATE sales.leads SET user_id = ? WHERE id = ?");
        $updateLeadQuery->execute([$lead->getUserId(), $lead->getId()]);
        $this->identityMap[$lead->getId()] = $lead;
    }

    public function delete(string $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteLeadQuery = $this->db->prepare("DELETE FROM sales.leads WHERE id = ?");
            $deleteLeadQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
