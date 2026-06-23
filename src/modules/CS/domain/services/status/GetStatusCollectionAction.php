<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetStatusCollectionAction
{
    public static function execute()
    {
        $repo = new StatusRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
