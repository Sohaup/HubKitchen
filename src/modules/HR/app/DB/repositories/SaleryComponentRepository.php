<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\SaleryComponentMapper;
use PostApi\modules\HR\domain\entities\SaleryComponent;
use PostApi\shared\templates\DB_Trait;

class SaleryComponentRepository {
    private SaleryComponentMapper $saleryComponentMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->saleryComponentMapper = new SaleryComponentMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->saleryComponentMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->saleryComponentMapper->findAll();
    }

    public function create(SaleryComponent $saleryComponent)
    {
        $this->saleryComponentMapper->create($saleryComponent);
    }

    public function update(SaleryComponent $saleryComponent)
    {
        $this->saleryComponentMapper->update($saleryComponent);
    }

    public function delete(int $id)
    {
        $this->saleryComponentMapper->delete($id);
    }
}