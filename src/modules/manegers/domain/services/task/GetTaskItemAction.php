<?php

namespace PostApi\modules\manegers\domain\services\task;

use PostApi\modules\manegers\app\DB\repositories\TaskRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTaskItemAction
{
    public static function execute(int $id)
    {
        $repo = new TaskRepository();
        $item = $repo->findOne($id);
        $serin = SerializeToSerin::serialize($item);
        return $serin;
    }
}
