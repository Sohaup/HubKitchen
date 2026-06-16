<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\AppraiselResultMapper;
use PostApi\modules\HR\domain\entities\AppraiselResult;
use PostApi\shared\templates\DB_Trait;

class AppraiselResultRepository {
     private AppraiselResultMapper $appraiselResultMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->appraiselResultMapper = new AppraiselResultMapper($this->postgre->pdo);
    }

    public function findOne(int $id)
    {
        return $this->appraiselResultMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->appraiselResultMapper->findAll();
    }

    public function create(AppraiselResult $appraiselResult)
    {
        $this->appraiselResultMapper->create($appraiselResult);
    }

    public function update(AppraiselResult $appraiselResult)
    {
        $this->appraiselResultMapper->update($appraiselResult);
    }

    public function delete(int $id)
    {
        $this->appraiselResultMapper->delete($id);
    }
}