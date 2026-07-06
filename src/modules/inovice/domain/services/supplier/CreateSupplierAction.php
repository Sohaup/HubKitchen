<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\app\http\requests\Request;

class CreateSupplierAction
{
    public static function execute(): Supplier
    {
        $request = new Request();
        $params = $request->body;
        $supplier = new Supplier();
        $supplier->setName($params['name'] ?? '');
        $repo = new SupplierRepository();
        $repo->create($supplier);
        return $supplier;
    }
}
