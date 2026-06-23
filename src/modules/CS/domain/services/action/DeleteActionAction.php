<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;

class DeleteActionAction
{
    public static function execute(string $id)
    {
        $repo = new ActionRepository();
        $repo->delete($id);
    }
}
