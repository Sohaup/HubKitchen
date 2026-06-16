<?php

namespace PostApi\modules\HR\app\DB\models;


use DateTime;
use PDO;
use PostApi\modules\HR\domain\entities\Shift;

class ShiftMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getShiftQuery = $this->db->prepare("SELECT * FROM HR.shifts WHERE id = ?");
        $getShiftQuery->execute([$id]);
        $shiftRawData = $getShiftQuery->fetch(PDO::FETCH_ASSOC);
        if ($shiftRawData) {
            $shift = new Shift();
            $shift->setId($shiftRawData['id']);
            $shift->setShiftName($shiftRawData['shift_name']);
            $shift->setStartTime($shiftRawData['start_time']);
            $shift->setEndTime($shiftRawData['end_time']);
            $shift->setBreakDuration($shiftRawData['break_duration_minutes']);
            $shift->setIsOverNight($shiftRawData['is_overnight']);
            $shift->setIsActive($shiftRawData['is_active']);
            $shift->setCreatedAt($shiftRawData['created_at']);
            $this->identityMap[$id] = $shift;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $getShiftsQuery = $this->db->prepare("SELECT * FROM HR.shifts");
        $getShiftsQuery->execute([]);
        $shiftsRawData = $getShiftsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($shiftsRawData as $shiftRawData) {
            $shift = new Shift();
            $shift->setId($shiftRawData['id']);
            $shift->setShiftName($shiftRawData['shift_name']);
            $shift->setStartTime($shiftRawData['start_time']);
            $shift->setEndTime($shiftRawData['end_time']);
            $shift->setBreakDuration($shiftRawData['break_duration_minutes']);
            $shift->setIsOverNight($shiftRawData['is_overnight']);
            $shift->setIsActive($shiftRawData['is_active']);
            $shift->setCreatedAt($shiftRawData['created_at']);
            $this->identityMap[$shiftRawData['id']] = $shift;
        }
        return $this->identityMap;
    }

    public function create(Shift $shift)
    {
        $createShiftQuery = $this->db->prepare("INSERT INTO HR.shifts(shift_name ,start_time , end_time , break_duration_minutes , is_overnight , is_active) VALUES(? , ? , ?, ? , ? , ?) RETURNING id");
        $createShiftQuery->execute([$shift->getShiftName(), $shift->getStartTime(), $shift->getEndTime(), $shift->getBreakDuration(), $shift->getIsOverNight() ? 1 : 0, $shift->getIsActive() ? 1 : 0]);
        $shiftId = $createShiftQuery->fetch(PDO::FETCH_ASSOC)['id'];
        if ($shiftId) {
            $shift->setId($shiftId);
            $this->identityMap[$shiftId] = $shift;
        }
    }

    public function update(Shift $shift)
    {
        if (isset($this->identityMap[$shift->getId()])) {
            $updateShiftQuery = $this->db->prepare("UPDATE HR.shifts SET shift_name = ? , start_time = ? ,  end_time = ? , break_duration_minutes = ?,  is_overnight = ? , is_active = ?  WHERE id = ?");
            $updateShiftQuery->execute([$shift->getShiftName(), $shift->getStartTime(), $shift->getEndTime(), $shift->getBreakDuration(), $shift->getIsOverNight() ? 1 : 0, $shift->getIsActive() ? 1 : 0, $shift->getId()]);
            $this->identityMap[$shift->getId()] = $shift;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteShiftQuery = $this->db->prepare("DELETE FROM HR.shifts WHERE id = ?");
            $deleteShiftQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
