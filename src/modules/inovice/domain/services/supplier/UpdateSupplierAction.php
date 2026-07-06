<?php

namespace PostApi\modules\inovice\domain\services\supplier;

use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateSupplierAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new SupplierRepository();
        $supplier = $repo->findOne($id);
        if (!$supplier) {
            throw new \Exception("supplier not found");
        }
        if (isset($params['name'])) {
            $supplier->setName($params['name']);
        }
        $repo->update($supplier);
        return $supplier;
    }
}
