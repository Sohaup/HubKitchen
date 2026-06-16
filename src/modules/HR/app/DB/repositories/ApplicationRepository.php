<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\ApplicationMapper;
use PostApi\modules\HR\domain\entities\Application;
use PostApi\shared\templates\DB_Trait;

class ApplicationRepository
{
    private ApplicationMapper $applicationMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->applicationMapper = new ApplicationMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->applicationMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->applicationMapper->findAll();
    }

    public function create(Application $application)
    {
        $this->applicationMapper->create($application);
    }

    public function update(Application $application)
    {
        $this->applicationMapper->update($application);
    }

    public function delete(int $id)
    {
        $this->applicationMapper->delete($id);
    }
}
