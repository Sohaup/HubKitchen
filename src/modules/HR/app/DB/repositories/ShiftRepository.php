<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\ShiftMapper;
use PostApi\modules\HR\domain\entities\Shift;
use PostApi\shared\templates\DB_Trait;

class ShiftRepository
{
    private ShiftMapper $shiftMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->shiftMapper = new ShiftMapper($this->postgre->pdo);
    }
    public function findOne(int $id)
    {
        return $this->shiftMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->shiftMapper->findAll();
    }

    public function create(Shift $shift)
    {
        $this->shiftMapper->create($shift);
    }

    public function update(Shift $shift)
    {
        $this->shiftMapper->update($shift);
    }

    public function delete(int $id)
    {
        $this->shiftMapper->delete($id);
    }
}
