<?php

namespace PostApi\modules\HR\app\DB\models;

use PDO;
use PostApi\modules\HR\domain\entities\SaleryComponent;

class SaleryComponentMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $getSaleryComponentQuery = $this->db->prepare("SELECT * FROM HR.selary_components WHERE id = ?");
        $getSaleryComponentQuery->execute([$id]);
        $saleryComponentRawData = $getSaleryComponentQuery->fetch(PDO::FETCH_ASSOC);
        if ($saleryComponentRawData) {
            $saleryComponent = new SaleryComponent($saleryComponentRawData['id'], $saleryComponentRawData['name'], $saleryComponentRawData['type'], $saleryComponentRawData['calc_type']);
            $this->identityMap[$id] = $saleryComponent;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $getSelariesComponentQuery = $this->db->prepare("SELECT * FROM HR.selary_components");
        $getSelariesComponentQuery->execute([]);
        $selariesComponentRawData = $getSelariesComponentQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($selariesComponentRawData as $saleryComponentRawData) {
            $saleryComponent = new SaleryComponent(id: $saleryComponentRawData['id'], name: $saleryComponentRawData['name'], type: $saleryComponentRawData['type'], calcType: $saleryComponentRawData['calc_type']);
            if (!isset($this->identityMap[$saleryComponent->getId()])) {
                $this->identityMap[$saleryComponent->getId()] = $saleryComponent;
            }
        }
        return $this->identityMap;
    }

    public function create(SaleryComponent $saleryComponent)
    {
        $createSaleryComponentQuery = $this->db->prepare("INSERT INTO HR.selary_components(name , type , calc_type) VALUES(? , ? , ? ) RETURNING id");
        $createSaleryComponentQuery->execute([$saleryComponent->getName(), $saleryComponent->getType(), $saleryComponent->getCalcType()]);
        $saleryComponentId = $createSaleryComponentQuery->fetch(PDO::FETCH_ASSOC)['id'];
        if ($saleryComponentId) {
            $saleryComponent->setId($saleryComponentId);
            $this->identityMap[$saleryComponentId] = $saleryComponent;
        }
    }

    public function update(SaleryComponent $saleryComponent)
    {
        if (isset($this->identityMap[$saleryComponent->getId()])) {
            $updateSaleryQuery = $this->db->prepare("UPDATE HR.selary_components SET name =? , type = ? , calc_type = ? WHERE id = ?");
            $updateSaleryQuery->execute([$saleryComponent->getName(), $saleryComponent->getType(), $saleryComponent->getCalcType() , $saleryComponent->getId()]);
            $this->identityMap[$saleryComponent->getId()] = $saleryComponent;
        }
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteSaleryQuery = $this->db->prepare("DELETE FROM HR.selary_components WHERE id = ?");
            $deleteSaleryQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
