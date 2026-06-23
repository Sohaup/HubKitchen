<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetActionCollectionAction
{
    public static function execute()
    {
        $repo = new ActionRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
