<?php

namespace PostApi\modules\manegers\domain\services\task;

use PostApi\modules\manegers\app\DB\repositories\TaskRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTaskCollectionAction
{
    public static function execute()
    {
        $repo = new TaskRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
