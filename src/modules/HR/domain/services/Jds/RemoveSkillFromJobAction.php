<?php
namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\modules\HR\app\DB\repositories\SkillRepository;

class RemoveSkillFromJobAction {
    public static function execute(int $skillId , int $jdId) {
        $jdRepository = new JobDescriptionRepository();
        $skillRepository = new SkillRepository();
        $skill = $skillRepository->findOne($skillId);
        $jd = $jdRepository->findOne($jdId);
        $jdRepository->removeSkillFromJob($skill , $jd);
    }
}