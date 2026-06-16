<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\auth\app\DB\models\UserMapper;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\HR\domain\entities\TurnCycle;

class TurnCycleMapper
{
    private array $identityMap = [];
    private EmployeeMapper $employeeMapper;


    public function __construct(private PDO $db)
    {
        $this->employeeMapper = new EmployeeMapper($this->db);
    }
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getTurnCycleQuery = $this->db->prepare("SELECT * FROM HR.turn_over_cycle WHERE id = ?");
        $getTurnCycleQuery->execute([$id]);
        $turnCycleRawData = $getTurnCycleQuery->fetch(PDO::FETCH_ASSOC);
        if ($turnCycleRawData) {
            $employee = $this->employeeMapper->findOne($turnCycleRawData['employee_id']);
            $turnCycle = new TurnCycle(id: $turnCycleRawData['id'], start_at: $turnCycleRawData['start_at'], leave_at: $turnCycleRawData['leave_at'], employee: $employee);
            $this->identityMap[$id] = $turnCycle;
            return $turnCycle;
        }
    }
    public function findAll()
    {
        $getTurnCycleQuery = $this->db->prepare("SELECT * FROM HR.turn_over_cycle ");
        $getTurnCycleQuery->execute([]);
        $turnCycleRawData = $getTurnCycleQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turnCycleRawData as $turnCycleRawData) {
            if (!isset($this->identityMap[$turnCycleRawData['id']])) {
                $employee = $this->employeeMapper->findOne($turnCycleRawData['employee_id']);
                $turnCycle = new TurnCycle(id: $turnCycleRawData['id'], start_at: $turnCycleRawData['start_at'], leave_at: $turnCycleRawData['leave_at'], employee: $employee);               
                $this->identityMap[$turnCycleRawData['id']] = $turnCycle;
            }
        }
        return $this->identityMap;
    }
    public function create(TurnCycle $turnCycle)
    {
        $createTurnCycleQuery = $this->db->prepare("INSERT INTO HR.turn_over_cycle (start_at , leave_at , employee_id) VALUES (? , ? , ?) RETURNING id ");
        $createTurnCycleQuery->execute([$turnCycle->getStartAt(), $turnCycle->getLeaveAt(), $turnCycle->getEmployee()->getId()]);
        $turnCycleId = $createTurnCycleQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $turnCycle->setId($turnCycleId);
        $this->identityMap[$turnCycleId] = $turnCycle;
        return $turnCycle;
    }

    public function update(TurnCycle $turnCycle)
    {
        if (isset($this->identityMap[$turnCycle->getId()])) {
            $updateTurnCycleQuery = $this->db->prepare("UPDATE HR.turn_over_cycle SET start_at = ? , leave_at = ?  , employee_id = ? WHERE id = ?");
            $updateTurnCycleQuery->execute([$turnCycle->getStartAt(), $turnCycle->getLeaveAt(), $turnCycle->getEmployee()->getId(), $turnCycle->getId()]);
            $this->identityMap[$turnCycle->getId()] = $turnCycle;
        }
    }
    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteTurnCycleQuery = $this->db->prepare("DELETE FROM HR.turn_over_cycle WHERE id = ?");
            $deleteTurnCycleQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
