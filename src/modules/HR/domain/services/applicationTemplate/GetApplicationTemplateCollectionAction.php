<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationTemplateCollectionAction
{
    public static function execute()
    {
        $repo = new ApplicationTemplateRepository();
        $entities = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($entities);
        return $serin;
    }
}
