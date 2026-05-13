<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\AppraiselResult;

class AppraiselResultMapper
{
    private array $identityMap = [];
    private ApplicationCycleMapper $applicationCycleMapper;
    private EvolutionCritiriaMapper $evolutionCritiriaMapper;
    private EmployeeMapper $employeeMapper;
    public function __construct(private PDO $db)
    {
        $this->applicationCycleMapper = new ApplicationCycleMapper($this->db);
        $this->evolutionCritiriaMapper = new EvolutionCritiriaMapper($this->db);
        $this->employeeMapper = new EmployeeMapper($this->db);
    }
    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getAppriaselReslutQuery = $this->db->prepare("SELECT * FROM HR.appraisal_results WHERE id = ?");
        $getAppriaselReslutQuery->execute([$id]);
        $appriaselReslutRawData = $getAppriaselReslutQuery->fetch(PDO::FETCH_ASSOC);
        if ($appriaselReslutRawData) {
            $cycle = $this->applicationCycleMapper->findOne($appriaselReslutRawData['cycle_id']);
            $employee = $this->employeeMapper->findOne($appriaselReslutRawData['employee_id']);
            $critiria = $this->evolutionCritiriaMapper->findOne($appriaselReslutRawData['critiria_id']);
            $appriaselReslut = new AppraiselResult(id: $appriaselReslutRawData['id'], cycle: $cycle, critiria: $critiria, employee: $employee, score: $appriaselReslutRawData['score'], mangerComments: $appriaselReslutRawData['manager_comment']);
            $this->identityMap[$appriaselReslutRawData['id']];
            return $appriaselReslut;
        }
    }

    public function findAll()
    {
        $getAppriaselReslutsQuery = $this->db->prepare("SELECT * FROM HR.appraisal_results ");
        $getAppriaselReslutsQuery->execute([]);
        $appriaselReslutsRawData = $getAppriaselReslutsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($appriaselReslutsRawData as $appriaselReslutRawData) {
            $cycle = $this->applicationCycleMapper->findOne($appriaselReslutRawData['cycle_id']);
            $employee = $this->employeeMapper->findOne($appriaselReslutRawData['employee_id']);
            $critiria = $this->evolutionCritiriaMapper->findOne($appriaselReslutRawData['critiria_id']);
            $appriaselReslut = new AppraiselResult(id: $appriaselReslutRawData['id'], cycle: $cycle, critiria: $critiria, employee: $employee, score: $appriaselReslutRawData['score'], mangerComments: $appriaselReslutRawData['manager_comment']);
            if (!isset($this->identityMap[$appriaselReslutRawData['id']])) {
                $this->identityMap[$appriaselReslutRawData['id']] = $appriaselReslut;
            }
        }
        return $this->identityMap;
    }

    public function create(AppraiselResult $appriaselReslut)
    {
        $createAppriaselReslutQuery = $this->db->prepare("INSERT INTO HR.appraisal_results(cycle_id , employee_id , critiria_id , score , manager_comment ) VALUES(? , ? , ? , ? , ?) RETURNING id ");
        $createAppriaselReslutQuery->execute([$appriaselReslut->getCycle()->getId(), $appriaselReslut->getEmployee()->getId(), $appriaselReslut->getCritiria()->getId(), $appriaselReslut->getScore(), $appriaselReslut->getManagerComments()]);
        $appriaselReslutId = $createAppriaselReslutQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $appriaselReslut->setId($appriaselReslutId);
        $this->identityMap[$appriaselReslut->getId()] = $appriaselReslut;
    }

    public function update(AppraiselResult $appriaselReslut)
    {
        if (isset($this->identityMap[$appriaselReslut->getId()])) {
            $updateAppriaselReslutQuery = $this->db->prepare("UPDATE HR.appraisal_results SET name = ? , starts_at = ? , ends_at = ? , status = ?  WHERE id = ?");
            $updateAppriaselReslutQuery->execute([$appriaselReslut->getCycle()->getId(), $appriaselReslut->getEmployee()->getId(), $appriaselReslut->getCritiria()->getId(), $appriaselReslut->getScore(), $appriaselReslut->getManagerComments(), $appriaselReslut->getId()]);
            $this->identityMap[$appriaselReslut->getId()] = $appriaselReslut;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteAppriaselReslutQuery = $this->db->prepare("DELETE FROM HR.appraisal_results WHERE id = ?");
            $deleteAppriaselReslutQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
