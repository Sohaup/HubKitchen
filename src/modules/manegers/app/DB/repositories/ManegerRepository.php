<?php
namespace PostApi\modules\manegers\app\DB\repositories;

use PostApi\modules\manegers\app\DB\models\ManegerMapper;
use PostApi\modules\manegers\domain\entities\Maneger;
use PostApi\shared\templates\DB_Trait;

class ManegerRepository
{
    use DB_Trait;
    private ManegerMapper $manegerMapper;
    public function __construct()
    {
        $this->initialize();
        $this->manegerMapper = new ManegerMapper($this->dataBase);
    }

    public function findOne(string $id) {
        return $this->manegerMapper->findOne($id);
    }

    public function findAll() {
        return $this->manegerMapper->findAll();
    }

    public function create(Maneger $maneger) {
        $this->manegerMapper->insert($maneger);
    }

    public function update(Maneger $maneger) {
        $this->manegerMapper->update($maneger);
    }

    public function delete(string $id) {
        $this->manegerMapper->delete($id);
    }
}
