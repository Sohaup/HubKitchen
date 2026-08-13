<?php
namespace PostApi\modules\inovice\app\DB\repositories;

use PostApi\modules\inovice\app\DB\models\PrucherMapper;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\shared\templates\DB_Trait;

class PrucherRepository
{
    use DB_Trait;
    private PrucherMapper $prucherMapper;
    public function __construct()
    {
        $this->initialize();
        $this->prucherMapper = new PrucherMapper($this->dataBase);
    }

    public function findOne(string $id) {
        return $this->prucherMapper->findOne($id);
    }

    public function findAll() {
        return $this->prucherMapper->findAll();
    }

    public function create(Prucher $prucher) {
        $this->prucherMapper->insert($prucher);
    }

    public function update(Prucher $prucher) {
        $this->prucherMapper->update($prucher);
    }

    public function delete(string $id) {
        $this->prucherMapper->delete($id);
    }
}
