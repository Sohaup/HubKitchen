<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
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
        try {
            $getApplicationCycleQuery = $this->db->prepare("SELECT * FROM HR.appraisal_cycles WHERE id = ?");
            $getApplicationCycleQuery->execute([$id]);
            $applicationCycleRawData = $getApplicationCycleQuery->fetch(PDO::FETCH_ASSOC);
            if ($applicationCycleRawData) {
                $applicationCycle = new ApplicationCycle(id: $applicationCycleRawData['id'], name: $applicationCycleRawData['name'], starts_at: $applicationCycleRawData['starts_at'], ends_at: $applicationCycleRawData['ends_at'], status: $applicationCycleRawData['status']);
                $this->identityMap[$applicationCycleRawData['id']];
                return $applicationCycle;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $filterApplicationCyclesQuery = "SELECT * FROM HR.appraisal_cycles";
        $whereClouses = [];
        $bindings = [];

        if (isset($criteria['id'])) {
            $whereClouses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (isset($criteria['name'])) {
            $whereClouses[] = "name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%";
        }

        if (isset($criteria['status'])) {
            $whereClouses[] = "status = ?";
            $bindings[] = $criteria['status'];
        }

        if (count($whereClouses) > 0) {
            $filterApplicationCyclesQuery .= " WHERE " . implode(" AND ", $whereClouses);
        }

        try {
            $getApplicationsQuery = $this->db->prepare($filterApplicationCyclesQuery);
            $getApplicationsQuery->execute($bindings);
            $applicationCyclesRawData = $getApplicationsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($applicationCyclesRawData as $applicationCycleRawData) {
                if (!isset($this->identityMap[$applicationCycleRawData['id']])) {
                    $applicationCycle = new ApplicationCycle(id: $applicationCycleRawData['id'], name: $applicationCycleRawData['name'], starts_at: $applicationCycleRawData['starts_at'], ends_at: $applicationCycleRawData['ends_at'], status: $applicationCycleRawData['status']);
                    $this->identityMap[$applicationCycleRawData['id']] = $applicationCycle;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(ApplicationCycle $applicationCycle)
    {
        try {
            $createApplicationCycleQuery = $this->db->prepare("INSERT INTO HR.appraisal_cycles(name , starts_at , ends_at , status ) VALUES(? , ? , ? , ?) RETURNING id ");
            $createApplicationCycleQuery->execute([$applicationCycle->getName(), $applicationCycle->getStartsAt(), $applicationCycle->getEndsAt(), $applicationCycle->getStatus()]);
            $applicationCycleId = $createApplicationCycleQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $applicationCycle->setId($applicationCycleId);
            $this->identityMap[$applicationCycle->getId()] = $applicationCycle;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(ApplicationCycle $applicationCycle)
    {
        try {
            $updateApplicationCycleQuery = $this->db->prepare("UPDATE HR.appraisal_cycles SET name = ? , starts_at = ? , ends_at = ? , status = ?  WHERE id = ?");
            $updateApplicationCycleQuery->execute([$applicationCycle->getName(), $applicationCycle->getStartsAt(), $applicationCycle->getEndsAt(), $applicationCycle->getStatus(), $applicationCycle->getId()]);
            $this->identityMap[$applicationCycle->getId()] = $applicationCycle;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $deleteApplicationCycleQuery = $this->db->prepare("DELETE FROM HR.appraisal_cycles WHERE id = ?");
            $deleteApplicationCycleQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
