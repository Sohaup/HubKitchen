<?php

namespace PostApi\modules\manegers\domain\services\task;

use PostApi\modules\manegers\app\DB\repositories\TaskRepository;

class DeleteTaskAction
{
    public static function execute(int $id)
    {
        $repo = new TaskRepository();
        $repo->delete($id);
    }
}
