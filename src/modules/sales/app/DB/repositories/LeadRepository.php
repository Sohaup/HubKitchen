<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\LeadMapper;
use PostApi\modules\sales\domain\entities\Lead;
use PostApi\shared\templates\DB_Trait;

class LeadRepository
{
    private LeadMapper $leadMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->leadMapper = new LeadMapper($this->dataBase);
    }

    public function findOne(string $id)
    {
        return $this->leadMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->leadMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->leadMapper->findBy($critiria);
    }

    public function create(Lead $lead)
    {
        $this->leadMapper->create($lead);
    }

    public function update(Lead $lead)
    {
        $this->leadMapper->update($lead);
    }

    public function delete(string $id)
    {
        $this->leadMapper->delete($id);
    }
}
