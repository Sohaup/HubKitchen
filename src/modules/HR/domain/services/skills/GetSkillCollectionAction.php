<?php

namespace PostApi\modules\HR\domain\services\skills;

use PostApi\modules\HR\app\DB\repositories\SkillRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSkillCollectionAction
{
    public static function execute(array $items = null)
    {
        $skillsRepository = new SkillRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $skillsRepository->findAll());
        return $serin;
    }
}
