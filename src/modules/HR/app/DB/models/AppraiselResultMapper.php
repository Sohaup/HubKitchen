<?php

namespace PostApi\modules\HR\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\HR\domain\entities\Addresse;
use PostApi\modules\HR\domain\entities\ApplicationCycle;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;
use PostApi\modules\HR\domain\entities\AppraiselResult;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;
use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\modules\HR\domain\entities\Shift;

class AppraiselResultMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getAppriaselReslutQuery = $this->db->prepare("SELECT * FROM HR.evoluation_view WHERE id = ?");
            $getAppriaselReslutQuery->execute([$id]);
            $appriaselReslutRawData = $getAppriaselReslutQuery->fetch(PDO::FETCH_ASSOC);
            if ($appriaselReslutRawData) {
                $cycle = new ApplicationCycle(id: $appriaselReslutRawData['cycle_id'], name: $appriaselReslutRawData['cycle_name'], starts_at: $appriaselReslutRawData['cycle_starts_at'], ends_at: $appriaselReslutRawData['cycle_ends_at'], status: $appriaselReslutRawData['cycle_status']);
                $employee = new Employee();
                $employee->setId($appriaselReslutRawData['employee_id']);
                $employee->setUserId($appriaselReslutRawData['employee_user_id']);
                $employee->setManagerId($appriaselReslutRawData['employee_manager_id']);
                $employee->setMartialStatus($appriaselReslutRawData['employee_martial_status']);
                $employee->setEmployeeStatus($appriaselReslutRawData['employee_status']);
                $addrese = new Addresse();
                $addrese->setCountry($appriaselReslutRawData['addrese_country']);
                $addrese->setCity($appriaselReslutRawData['addrese_city']);
                $addrese->setFlat($appriaselReslutRawData['addrese_flat']);
                $addrese->setStreet($appriaselReslutRawData['addrese_street']);
                $addrese->setId($appriaselReslutRawData['addresse_id']);
                $employee->setAddress($addrese);
                $job = new JobDescription();
                $job->setId($appriaselReslutRawData['employee_job_description_id']);
                $job->setName($appriaselReslutRawData['jd_name']);
                $shift = new Shift();
                $shift->setId($appriaselReslutRawData['jd_shift_id']);
                $shift->setShiftName($appriaselReslutRawData['shift_name']);
                $shift->setStartTime($appriaselReslutRawData['shift_start_time']);
                $shift->setEndTime($appriaselReslutRawData['shift_end_time']);
                $shift->setBreakDuration($appriaselReslutRawData['shift_break_duration_by_minutes']);
                $shift->setIsActive($appriaselReslutRawData['shift_is_active']);
                $shift->setIsOverNight($appriaselReslutRawData['shift_is_overnight']);
                $shift->setCreatedAt($appriaselReslutRawData['shift_created_at']);
                $job->setShift($shift);
                $employee->setJob($job);
                $department = new Department();
                $department->setId($appriaselReslutRawData['department_id']);
                $department->setName($appriaselReslutRawData['department_name']);
                $employee->setDepartment($department);
                $application = new ApplicationTemplate(id: $appriaselReslutRawData['template_id'], title: $appriaselReslutRawData['template_title'], description: $appriaselReslutRawData['template_description']);
                $critiria = new EvolutionCritiria(id: $appriaselReslutRawData['critiria_id'], critiria: $appriaselReslutRawData['critiria'], weight: $appriaselReslutRawData['critiria_weight'], application: $application);
                $appriaselReslut = new AppraiselResult(id: $appriaselReslutRawData['id'], cycle: $cycle, critiria: $critiria, employee: $employee, score: $appriaselReslutRawData['score'], mangerComments: $appriaselReslutRawData['manager_comment']);
                $this->identityMap[$appriaselReslutRawData['id']] = $appriaselReslut;
                return $appriaselReslut;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getAppriaselReslutsQuery = $this->db->prepare("SELECT * FROM HR.evoluation_view");
            $getAppriaselReslutsQuery->execute([]);
            $appriaselReslutsRawData = $getAppriaselReslutsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($appriaselReslutsRawData as $appriaselReslutRawData) {
                $cycle = new ApplicationCycle(id: $appriaselReslutRawData['cycle_id'], name: $appriaselReslutRawData['cycle_name'], starts_at: $appriaselReslutRawData['cycle_starts_at'], ends_at: $appriaselReslutRawData['cycle_ends_at'], status: $appriaselReslutRawData['cycle_status']);
                $employee = new Employee();
                $employee->setId($appriaselReslutRawData['employee_id']);
                $employee->setUserId($appriaselReslutRawData['employee_user_id']);
                $employee->setManagerId($appriaselReslutRawData['employee_manager_id']);
                $employee->setMartialStatus($appriaselReslutRawData['employee_martial_status']);
                $employee->setEmployeeStatus($appriaselReslutRawData['employee_status']);
                $addrese = new Addresse();
                $addrese->setCountry($appriaselReslutRawData['addrese_country']);
                $addrese->setCity($appriaselReslutRawData['addrese_city']);
                $addrese->setFlat($appriaselReslutRawData['addrese_flat']);
                $addrese->setStreet($appriaselReslutRawData['addrese_street']);
                $addrese->setId($appriaselReslutRawData['addresse_id']);
                $employee->setAddress($addrese);
                $job = new JobDescription();
                $job->setId($appriaselReslutRawData['employee_job_description_id']);
                $job->setName($appriaselReslutRawData['jd_name']);
                $shift = new Shift();
                $shift->setId($appriaselReslutRawData['jd_shift_id']);
                $shift->setShiftName($appriaselReslutRawData['shift_name']);
                $shift->setStartTime($appriaselReslutRawData['shift_start_time']);
                $shift->setEndTime($appriaselReslutRawData['shift_end_time']);
                $shift->setBreakDuration($appriaselReslutRawData['shift_break_duration_by_minutes']);
                $shift->setIsActive($appriaselReslutRawData['shift_is_active']);
                $shift->setIsOverNight($appriaselReslutRawData['shift_is_overnight']);
                $shift->setCreatedAt($appriaselReslutRawData['shift_created_at']);
                $job->setShift($shift);
                $department = new Department();
                $department->setId($appriaselReslutRawData['department_id']);
                $department->setName($appriaselReslutRawData['department_name']);
                $employee->setDepartment($department);
                $application = new ApplicationTemplate(id: $appriaselReslutRawData['template_id'], title: $appriaselReslutRawData['template_title'], description: $appriaselReslutRawData['template_description']);
                $critiria = new EvolutionCritiria(id: $appriaselReslutRawData['critiria_id'], critiria: $appriaselReslutRawData['critiria'], weight: $appriaselReslutRawData['critiria_weight'], application: $application);
                $appriaselReslut = new AppraiselResult(id: $appriaselReslutRawData['id'], cycle: $cycle, critiria: $critiria, employee: $employee, score: $appriaselReslutRawData['score'], mangerComments: $appriaselReslutRawData['manager_comment']);
                if (!isset($this->identityMap[$appriaselReslutRawData['id']])) {
                    $this->identityMap[$appriaselReslutRawData['id']] = $appriaselReslut;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(AppraiselResult $appriaselReslut)
    {
        try {
            $createAppriaselReslutQuery = $this->db->prepare("INSERT INTO HR.appraisal_results(cycle_id , employee_id , critiria_id , score , manager_comment ) VALUES(? , ? , ? , ? , ?) RETURNING id ");
            $createAppriaselReslutQuery->execute([$appriaselReslut->getCycle()->getId(), $appriaselReslut->getEmployee()->getId(), $appriaselReslut->getCritiria()->getId(), $appriaselReslut->getScore(), $appriaselReslut->getManagerComments()]);
            $appriaselReslutId = $createAppriaselReslutQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $appriaselReslut->setId($appriaselReslutId);
            $this->identityMap[$appriaselReslut->getId()] = $appriaselReslut;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(AppraiselResult $appriaselReslut)
    {
        try {
            $updateAppriaselReslutQuery = $this->db->prepare("UPDATE HR.appraisal_results SET cycle_id= ? , employee_id = ? , critiria_id = ? , score = ?  , manager_comment = ? WHERE id = ?");
            $updateAppriaselReslutQuery->execute([$appriaselReslut->getCycle()->getId(), $appriaselReslut->getEmployee()->getId(), $appriaselReslut->getCritiria()->getId(), $appriaselReslut->getScore(), $appriaselReslut->getManagerComments(), $appriaselReslut->getId()]);
            $this->identityMap[$appriaselReslut->getId()] = $appriaselReslut;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $deleteAppriaselReslutQuery = $this->db->prepare("DELETE FROM HR.appraisal_results WHERE id = ?");
            $deleteAppriaselReslutQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
