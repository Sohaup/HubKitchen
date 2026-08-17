<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Salery;
use PostApi\modules\HR\domain\entities\Shift;

class SaleryMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getSaleryQuery = $this->db->prepare("SELECT * FROM HR.employees_view  WHERE selary_id = ?");
            $getSaleryQuery->execute([$id]);
            $saleryRawData = $getSaleryQuery->fetch(PDO::FETCH_ASSOC);
            if ($saleryRawData) {
                $employee = new Employee();
                $job = new JobDescription();
                $shift = new Shift();
                $shift->create(id: $saleryRawData['shift_id'], shiftName: $saleryRawData['shift_name'], startTime: $saleryRawData['shift_start_time'], endTime: $saleryRawData['shift_end_time'], breakDuration: $saleryRawData['shift_break_duration_by_minutes'], isOverNight: $saleryRawData['shift_is_overnight'], isActive: $saleryRawData['shift_is_active'], createdAt: $saleryRawData['shift_created_at']);
                $job->create($saleryRawData['jd_id'], $saleryRawData['jd_name'], $shift);
                $department = new Department();
                $department->create($saleryRawData['department_id'], $saleryRawData['department_name']);
                $addresse = new Addresse();
                $addresse->create($saleryRawData['addresse_id'], $saleryRawData['country'], $saleryRawData['city'], $saleryRawData['street'], $saleryRawData['flat']);
                $employee->create($saleryRawData['id'], $saleryRawData['employee_status'], $saleryRawData['martial_status'], $saleryRawData['user_id'], $job, $saleryRawData['manager_id'], $saleryRawData['employeed_at'], $department, $addresse);
                $salery = new Salery(id: $saleryRawData['selary_id'], employee: $employee, salery: $saleryRawData['selary']);
                $this->identityMap[$id] = $salery;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getSelariesQuery = $this->db->prepare("SELECT * FROM HR.employees_view  WHERE selary_id IS NOT NULL");
            $getSelariesQuery->execute([]);
            $selariesRawData = $getSelariesQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($selariesRawData as $saleryRawData) {
                $employee = new Employee();
                $job = new JobDescription();
                $shift = new Shift();
                $shift->create(id: $saleryRawData['shift_id'], shiftName: $saleryRawData['shift_name'], startTime: $saleryRawData['shift_start_time'], endTime: $saleryRawData['shift_end_time'], breakDuration: $saleryRawData['shift_break_duration_by_minutes'], isOverNight: $saleryRawData['shift_is_overnight'], isActive: $saleryRawData['shift_is_active'], createdAt: $saleryRawData['shift_created_at']);
                $job->create($saleryRawData['jd_id'], $saleryRawData['jd_name'], $shift);
                $department = new Department();
                $department->create($saleryRawData['department_id'], $saleryRawData['department_name']);
                $addresse = new Addresse();
                $addresse->create($saleryRawData['addresse_id'], $saleryRawData['country'], $saleryRawData['city'], $saleryRawData['street'], $saleryRawData['flat']);
                $employee->create($saleryRawData['id'], $saleryRawData['employee_status'], $saleryRawData['martial_status'], $saleryRawData['user_id'], $job, $saleryRawData['manager_id'], $saleryRawData['employeed_at'], $department, $addresse);
                $salery = new Salery(id: $saleryRawData['selary_id'], employee: $employee, salery: $saleryRawData['selary']);
                $this->identityMap[$saleryRawData['selary_id']] = $salery;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Salery $salery)
    {
        try {
            $createShiftQuery = $this->db->prepare("INSERT INTO HR.selaries(employee_id , selary) VALUES(? , ? ) RETURNING id");
            $createShiftQuery->execute([$salery->getEmployee()->getId(), $salery->getSalery()]);
            $saleryId = $createShiftQuery->fetch(PDO::FETCH_ASSOC)['id'];
            if ($saleryId) {
                $salery->setId($saleryId);
                $this->identityMap[$saleryId] = $salery;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Salery $salery)
    {
        try {
            if (isset($this->identityMap[$salery->getId()])) {
                $updateSaleryQuery = $this->db->prepare("UPDATE HR.selaries SET employee_id =? , selary = ? WHERE id = ?");
                $updateSaleryQuery->execute([$salery->getEmployee()->getId(), $salery->getSalery(), $salery->getId()]);
                $this->identityMap[$salery->getId()] = $salery;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteSaleryQuery = $this->db->prepare("DELETE FROM HR.selaries WHERE id = ?");
                $deleteSaleryQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
