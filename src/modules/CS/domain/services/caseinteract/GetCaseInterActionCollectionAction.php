<?php

namespace PostApi\modules\CS\domain\services\caseinteract;

use PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCaseInterActionCollectionAction
{
    public static function execute()
    {
        $repo = new CaseInterActionRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
