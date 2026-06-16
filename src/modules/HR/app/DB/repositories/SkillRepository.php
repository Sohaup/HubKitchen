<?php
namespace PostApi\modules\HR\app\DB\repositories;

use PostApi\modules\HR\app\DB\models\SkillMapper;
use PostApi\modules\HR\domain\entities\Skill;
use PostApi\shared\templates\DB_Trait;

class SkillRepository {
    private SkillMapper $skillMapper;
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
        $this->skillMapper = new SkillMapper($this->postgre->pdo);
    }
    public function findOne(int $id) {
        return $this->skillMapper->findOne($id);
    }
    public function findAll() {
        return $this->skillMapper->findAll();
    }
    public function create(Skill $skill) {
        $this->skillMapper->create($skill);
    }
    public function update(Skill $skill) {
        $this->skillMapper->update($skill);
    }
    public function delete(int $id) {
        $this->skillMapper->delete($id);
    }
}