<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSupplierItemAction
{
    public static function execute(string $id)
    {
        $repo = new SupplierRepository();
        $supplier = $repo->findOne($id);
        return SerializeToSerin::serialize($supplier);
    }
}
