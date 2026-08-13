<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Shift;
use PostApi\modules\HR\domain\entities\TurnCycle;

class TurnCycleMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getTurnCycleQuery = $this->db->prepare("SELECT * FROM HR.employees_view WHERE turn_cycle_id = ?");
        $getTurnCycleQuery->execute([$id]);
        $turnCycleRawData = $getTurnCycleQuery->fetch(PDO::FETCH_ASSOC);
        if ($turnCycleRawData) {
            $employee = new Employee();
            $job = new JobDescription();
            $shift = new Shift();
            $shift->create(id: $turnCycleRawData['shift_id'], shiftName: $turnCycleRawData['shift_name'], startTime: $turnCycleRawData['shift_start_time'], endTime: $turnCycleRawData['shift_end_time'], breakDuration: $turnCycleRawData['shift_break_duration_by_minutes'], isOverNight: $turnCycleRawData['shift_is_overnight'], isActive: $turnCycleRawData['shift_is_active'], createdAt: $turnCycleRawData['shift_created_at']);
            $job->create($turnCycleRawData['jd_id'], $turnCycleRawData['jd_name'], $shift);
            $department = new Department();
            $department->create($turnCycleRawData['department_id'], $turnCycleRawData['department_name']);
            $addresse = new Addresse();
            $addresse->create($turnCycleRawData['addresse_id'], $turnCycleRawData['country'], $turnCycleRawData['city'], $turnCycleRawData['street'], $turnCycleRawData['flat']);
            $employee->create($turnCycleRawData['id'], $turnCycleRawData['employee_status'], $turnCycleRawData['martial_status'], $turnCycleRawData['user_id'], $job, $turnCycleRawData['manager_id'], $turnCycleRawData['employeed_at'], $department, $addresse);
            $turnCycle = new TurnCycle(id: $turnCycleRawData['turn_cycle_id'], start_at: $turnCycleRawData['start_at'], leave_at: $turnCycleRawData['leave_at'], employee: $employee);
            $this->identityMap[$id] = $turnCycle;
            return $turnCycle;
        }
    }
    public function findAll()
    {
        $getTurnCycleQuery = $this->db->prepare("SELECT * FROM HR.employees_view WHERE turn_cycle_id IS NOT NULL  ");
        $getTurnCycleQuery->execute([]);
        $turnCycleRawData = $getTurnCycleQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turnCycleRawData as $turnCycleRawData) {
            if (!isset($this->identityMap[$turnCycleRawData['turn_cycle_id']])) {
                $employee = new Employee();
                $job = new JobDescription();
                $shift = new Shift();
                $shift->create(id: $turnCycleRawData['shift_id'], shiftName: $turnCycleRawData['shift_name'], startTime: $turnCycleRawData['shift_start_time'], endTime: $turnCycleRawData['shift_end_time'], breakDuration: $turnCycleRawData['shift_break_duration_by_minutes'], isOverNight: $turnCycleRawData['shift_is_overnight'], isActive: $turnCycleRawData['shift_is_active'], createdAt: $turnCycleRawData['shift_created_at']);
                $job->create($turnCycleRawData['jd_id'], $turnCycleRawData['jd_name'], $shift);
                $department = new Department();
                $department->create($turnCycleRawData['department_id'], $turnCycleRawData['department_name']);
                $addresse = new Addresse();
                $addresse->create($turnCycleRawData['addresse_id'], $turnCycleRawData['country'], $turnCycleRawData['city'], $turnCycleRawData['street'], $turnCycleRawData['flat']);
                $employee->create($turnCycleRawData['id'], $turnCycleRawData['employee_status'], $turnCycleRawData['martial_status'], $turnCycleRawData['user_id'], $job, $turnCycleRawData['manager_id'], $turnCycleRawData['employeed_at'], $department, $addresse);
                $turnCycle = new TurnCycle(id: $turnCycleRawData['turn_cycle_id'], start_at: $turnCycleRawData['start_at'], leave_at: $turnCycleRawData['leave_at'], employee: $employee);
                $this->identityMap[$turnCycleRawData['turn_cycle_id']] = $turnCycle;
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
