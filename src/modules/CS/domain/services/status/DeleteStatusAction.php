<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;

class DeleteStatusAction
{
    public static function execute(string $id)
    {
        $repo = new StatusRepository();
        $repo->delete($id);
    }
}
