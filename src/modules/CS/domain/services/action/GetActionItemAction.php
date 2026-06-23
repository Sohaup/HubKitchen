<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetActionItemAction
{
    public static function execute(string $id)
    {
        $repo = new ActionRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
