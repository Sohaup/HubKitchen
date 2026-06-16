<?php

namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSkillCollectionAction
{
    public static function execute()
    {
        $skillsRepository = new SkillRepository();
        $skills = $skillsRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($skills);
        return $serin;
    }
}
