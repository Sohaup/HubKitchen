<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;

class DeleteSupplierAction
{
    public static function execute(string $id)
    {
        $repo = new SupplierRepository();
        $repo->delete($id);
    }
}
