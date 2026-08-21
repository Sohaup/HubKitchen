<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAppraiselResultCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new AppraiselResultRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
    }
}
