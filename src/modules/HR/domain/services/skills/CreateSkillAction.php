<?php

namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;
use PostApi\modules\HR\domain\entities\Skill;

class CreateSkillAction
{
    public static function execute(array $params)
    {
        $skillsRepository = new SkillRepository();
        $skill = new Skill();
        $skill->setName($params['name']);
        $skillsRepository->create($skill);
        return $skill;
    }
}
