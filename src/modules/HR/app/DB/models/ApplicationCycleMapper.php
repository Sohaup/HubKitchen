<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\ApplicationCycle;

class ApplicationCycleMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getApplicationCycleQuery = $this->db->prepare("SELECT * FROM HR.appraisal_cycles WHERE id = ?");
        $getApplicationCycleQuery->execute([$id]);
        $applicationCycleRawData = $getApplicationCycleQuery->fetch(PDO::FETCH_ASSOC);
        if ($applicationCycleRawData) {
            $applicationCycle = new ApplicationCycle(id: $applicationCycleRawData['id'], name: $applicationCycleRawData['name'], starts_at: $applicationCycleRawData['starts_at'], ends_at: $applicationCycleRawData['ends_at'], status: $applicationCycleRawData['status']);
            $this->identityMap[$applicationCycleRawData['id']];
            return $applicationCycle;
        }
    }

    public function findAll()
    {
        $getApplicationsQuery = $this->db->prepare("SELECT * FROM HR.appraisal_cycles ");
        $getApplicationsQuery->execute([]);
        $applicationCyclesRawData = $getApplicationsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($applicationCyclesRawData as $applicationCycleRawData) {
            $applicationCycle = new ApplicationCycle(id: $applicationCycleRawData['id'], name: $applicationCycleRawData['name'], starts_at: $applicationCycleRawData['starts_at'], ends_at: $applicationCycleRawData['ends_at'], status: $applicationCycleRawData['status']);
            if (!isset($this->identityMap[$applicationCycleRawData['id']])) {
                $this->identityMap[$applicationCycleRawData['id']] = $applicationCycle;
            }
        }
        return $this->identityMap;
    }

    public function create(ApplicationCycle $applicationCycle)
    {
        $createApplicationCycleQuery = $this->db->prepare("INSERT INTO HR.appraisal_cycles(name , starts_at , ends_at , status ) VALUES(? , ? , ? , ?) RETURNING id ");
        $createApplicationCycleQuery->execute([$applicationCycle->getName() , $applicationCycle->getStartsAt() , $applicationCycle->getEndsAt() , $applicationCycle->getStatus()]);
        $applicationCycleId = $createApplicationCycleQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $applicationCycle->setId($applicationCycleId);
        $this->identityMap[$applicationCycle->getId()] = $applicationCycle;
    }

    public function update(ApplicationCycle $applicationCycle)
    {
        if (isset($this->identityMap[$applicationCycle->getId()])) {
            $updateApplicationCycleQuery = $this->db->prepare("UPDATE HR.appraisal_cycles SET name = ? , starts_at = ? , ends_at = ? , status = ?  WHERE id = ?");
            $updateApplicationCycleQuery->execute([$applicationCycle->getName() , $applicationCycle->getStartsAt() , $applicationCycle->getEndsAt() , $applicationCycle->getStatus() , $applicationCycle->getId()]);
            $this->identityMap[$applicationCycle->getId()] = $applicationCycle;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteApplicationCycleQuery = $this->db->prepare("DELETE FROM HR.appraisal_cycles WHERE id = ?");
            $deleteApplicationCycleQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
