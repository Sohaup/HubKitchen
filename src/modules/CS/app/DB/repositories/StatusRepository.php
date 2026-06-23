<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\StatusMapper;
use PostApi\modules\CS\domain\entities\Status;
use PostApi\shared\templates\DB_Trait;

class StatusRepository
{
    use DB_Trait;
    private StatusMapper $statusMapper;
    public function __construct()
    {
        $this->initialize();
        $this->statusMapper = new StatusMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->statusMapper->findOne($id);
    }

    public function findAll() {
        return $this->statusMapper->findAll();
    }

    public function create(Status $status) {
        $this->statusMapper->insert($status);
    }

    public function update(Status $status) {
        $this->statusMapper->update($status);
    }

    public function delete(string $id) {
        $this->statusMapper->delete($id);
    }
}
