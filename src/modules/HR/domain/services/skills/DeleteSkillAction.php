<?php
namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;

class DeleteSkillAction {
    public static function execute(int $id) {
        $skillsRepository = new SkillRepository();
        $skill = $skillsRepository->findOne($id);
        if ($skill) {
            $skillsRepository->delete($id);
        }
    }
}