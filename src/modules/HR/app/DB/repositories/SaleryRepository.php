<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\SaleryMapper;
use PostApi\modules\HR\domain\entities\Salery;
use PostApi\shared\templates\DB_Trait;

class SaleryRepository
{
    private SaleryMapper $saleryMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->saleryMapper = new SaleryMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->saleryMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->saleryMapper->findAll();
    }

    public function create(Salery $salery)
    {
        $this->saleryMapper->create($salery);
    }

    public function update(Salery $salery)
    {
        $this->saleryMapper->update($salery);
    }

    public function delete(int $id)
    {
        $this->saleryMapper->delete($id);
    }
}
