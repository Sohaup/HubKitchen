<?php

namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;

class UpdateSkillAction
{
    public static function execute(int $id , array $params)
    {       
        $skillsRepository = new SkillRepository();
        $skill = $skillsRepository->findOne($id);
        if ($skill) {
            $skill->setName($params['name']);
            $skillsRepository->update($skill);
        }
    }
}
