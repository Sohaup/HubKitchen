<?php

namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\ApplicationTemplateMapper;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;
use PostApi\shared\templates\DB_Trait;

class ApplicationTemplateRepository
{
    private ApplicationTemplateMapper $applicationTemplateMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->applicationTemplateMapper = new ApplicationTemplateMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->applicationTemplateMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->applicationTemplateMapper->findAll();
    }

    public function create(ApplicationTemplate $applicationTemplate)
    {
        $this->applicationTemplateMapper->create($applicationTemplate);
    }

    public function update(ApplicationTemplate $applicationTemplate)
    {
        $this->applicationTemplateMapper->update($applicationTemplate);
    }

    public function delete(int $id)
    {
        $this->applicationTemplateMapper->delete($id);
    }
}
