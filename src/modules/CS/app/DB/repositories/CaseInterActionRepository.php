<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\CaseInterActionMapper;
use PostApi\modules\CS\domain\entities\CaseInterAction;
use PostApi\shared\templates\DB_Trait;

class CaseInterActionRepository
{
    use DB_Trait;
    private CaseInterActionMapper $mapper;
    public function __construct()
    {
        $this->initialize();
        $this->mapper = new CaseInterActionMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->mapper->findOne($id);
    }

    public function findAll() {
        return $this->mapper->findAll();
    }

    public function create(CaseInterAction $entity) {
        $this->mapper->insert($entity);
    }

    public function update(CaseInterAction $entity) {
        $this->mapper->update($entity);
    }

    public function delete(string $id) {
        $this->mapper->delete($id);
    }
}
