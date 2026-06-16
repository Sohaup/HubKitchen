<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\Salery;

class SaleryMapper
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
        $getSaleryQuery = $this->db->prepare("SELECT * FROM HR.selaries WHERE id = ?");
        $getSaleryQuery->execute([$id]);
        $saleryRawData = $getSaleryQuery->fetch(PDO::FETCH_ASSOC);
        if ($saleryRawData) {
            $employee = $this->employeeMapper->findOne($saleryRawData['employee_id']);
            $salery = new Salery(id: $saleryRawData['id'] , employee:$employee , salery:$saleryRawData['selary']);
            $this->identityMap[$id] = $salery;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $getSelariesQuery = $this->db->prepare("SELECT * FROM HR.selaries");
        $getSelariesQuery->execute([]);
        $selariesRawData = $getSelariesQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($selariesRawData as $saleryRawData) {
            $employee = $this->employeeMapper->findOne($saleryRawData['employee_id']);
            $salery = new Salery(id: $saleryRawData['id'] , employee:$employee , salery:$saleryRawData['selary']);
            $this->identityMap[$saleryRawData['id']] = $salery;
        }
        return $this->identityMap;
    }

    public function create(Salery $salery)
    {
        $createShiftQuery = $this->db->prepare("INSERT INTO HR.selaries(employee_id , selary) VALUES(? , ? ) RETURNING id");
        $createShiftQuery->execute([$salery->getEmployee()->getId() , $salery->getSalery() ]);
        $saleryId = $createShiftQuery->fetch(PDO::FETCH_ASSOC)['id'];
        if ($saleryId) {
            $salery->setId($saleryId);
            $this->identityMap[$saleryId] = $salery;
        }
    }

    public function update(Salery $salery)
    {
        if (isset($this->identityMap[$salery->getId()])) {
            $updateSaleryQuery = $this->db->prepare("UPDATE HR.selaries SET employee_id =? , selary = ? WHERE id = ?");
            $updateSaleryQuery->execute([$salery->getEmployee()->getId() , $salery->getSalery() , $salery->getId()]);
            $this->identityMap[$salery->getId()] = $salery;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteSaleryQuery = $this->db->prepare("DELETE FROM HR.selaries WHERE id = ?");
            $deleteSaleryQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
