<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\ActionMapper;
use PostApi\modules\CS\domain\entities\Action;
use PostApi\shared\templates\DB_Trait;

class ActionRepository
{
    use DB_Trait;
    private ActionMapper $actionMapper;
    public function __construct()
    {
        $this->initialize();
        $this->actionMapper = new ActionMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->actionMapper->findOne($id);
    }

    public function findAll() {
        return $this->actionMapper->findAll();
    }

    public function create(Action $action) {
        $this->actionMapper->insert($action);
    }

    public function update(Action $action) {
        $this->actionMapper->update($action);
    }

    public function delete(string $id) {
        $this->actionMapper->delete($id);
    }
}
