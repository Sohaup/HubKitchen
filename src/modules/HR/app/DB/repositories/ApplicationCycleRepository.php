<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\ApplicationCycleMapper;
use PostApi\modules\HR\domain\entities\ApplicationCycle;
use PostApi\shared\templates\DB_Trait;

class ApplicationCycleRepository {
    private ApplicationCycleMapper $applicationCycleMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->applicationCycleMapper = new ApplicationCycleMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->applicationCycleMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->applicationCycleMapper->findAll();
    }

    public function create(ApplicationCycle $applicationCycle)
    {
        $this->applicationCycleMapper->create($applicationCycle);
    }

    public function update(ApplicationCycle $applicationCycle)
    {
        $this->applicationCycleMapper->update($applicationCycle);
    }

    public function delete(int $id)
    {
        $this->applicationCycleMapper->delete($id);
    }
}