<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAppraiselResultItemAction
{
    public static function execute(int $id)
    {
        $repo = new AppraiselResultRepository();
        $item = $repo->findOne($id);
        $serin = SerializeToSerin::serialize($item);
        return $serin;
    }
}
