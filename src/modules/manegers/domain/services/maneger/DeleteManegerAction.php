<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;

class DeleteManegerAction
{
    public static function execute(string $id)
    {
        $repo = new ManegerRepository();
        $repo->delete($id);
    }
}
