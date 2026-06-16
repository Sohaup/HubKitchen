<?php
namespace PostApi\modules\HR\domain\services\skills;
use PostApi\modules\HR\app\DB\repositories\SkillRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSkillItemAction {
    public static function execute(int $id) {
        $skillsRepository = new SkillRepository();
        $skill = $skillsRepository->findOne($id);
        $serin = SerializeToSerin::serialize($skill);
        return $serin;
    }
}