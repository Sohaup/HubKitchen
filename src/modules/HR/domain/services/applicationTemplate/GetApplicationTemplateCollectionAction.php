<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationTemplateCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new ApplicationTemplateRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
    }
}
