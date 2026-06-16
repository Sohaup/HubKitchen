<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAppraiselResultCollectionAction
{
    public static function execute()
    {
        $repo = new AppraiselResultRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
