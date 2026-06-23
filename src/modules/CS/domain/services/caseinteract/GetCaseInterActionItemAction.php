<?php

namespace PostApi\modules\CS\domain\services\caseinteract;

use PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCaseInterActionItemAction
{
    public static function execute(string $id)
    {
        $repo = new CaseInterActionRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
