<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\modules\inovice\domain\entities\Supplier;

class CreateSupplierAction
{
    public static function execute(array $params): Supplier
    {        
        $supplier = new Supplier();
        $supplier->setName($params['name'] ?? '');
        $repo = new SupplierRepository();
        $repo->create($supplier);
        return $supplier;
    }
}
