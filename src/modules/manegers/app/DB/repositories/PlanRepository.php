<?php
namespace PostApi\modules\manegers\app\DB\repositories;

use PostApi\modules\manegers\app\DB\models\PlanMapper;
use PostApi\modules\manegers\domain\entities\Plan;
use PostApi\shared\templates\DB_Trait;

class PlanRepository
{
    use DB_Trait;
    private PlanMapper $planMapper;
    public function __construct()
    {
        $this->initialize();
        $this->planMapper = new PlanMapper($this->postgre->pdo);
    }

    public function findOne(int $id) {
        return $this->planMapper->findOne($id);
    }

    public function findAll() {
        return $this->planMapper->findAll();
    }

    public function create(Plan $plan) {
        $this->planMapper->insert($plan);
    }

    public function update(Plan $plan) {
        $this->planMapper->update($plan);
    }

    public function delete(int $id) {
        $this->planMapper->delete($id);
    }
}
