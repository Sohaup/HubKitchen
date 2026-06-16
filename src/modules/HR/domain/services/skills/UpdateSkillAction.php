<?php

namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateSkillAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $skillsRepository = new SkillRepository();
        $skill = $skillsRepository->findOne($id);
        if ($skill) {
            $skill->setName($params['name']);
            $skillsRepository->update($skill);
        }
    }
}
