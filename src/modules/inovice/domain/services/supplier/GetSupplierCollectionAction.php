<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSupplierCollectionAction
{
    public static function execute()
    {
        $repo = new SupplierRepository();
        $suppliers = $repo->findAll();
        return SerializeToSerin::serializeCollection($suppliers);
    }
}
