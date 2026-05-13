<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\TurnCycleMapper;
use PostApi\modules\HR\domain\entities\TurnCycle;
use PostApi\shared\templates\DB_Trait;

class TurnCycleRepository
{
    private TurnCycleMapper $turnCycleMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->turnCycleMapper = new TurnCycleMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->turnCycleMapper->findOne($id);
    }
    public function findAll()
    {
        return $this->turnCycleMapper->findAll();
    }
    public function create(TurnCycle $turnCycle)
    {
        $this->turnCycleMapper->create($turnCycle);
    }
    public function update(TurnCycle $turnCycle)
    {
        $this->turnCycleMapper->update($turnCycle);
    }
    public function delete(int $id)
    {
        $this->turnCycleMapper->delete($id);
    }
}
