<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;

class DeletePrucherAction
{
    public static function execute(string $id)
    {
        $repo = new PrucherRepository();
        $repo->delete($id);
    }
}
