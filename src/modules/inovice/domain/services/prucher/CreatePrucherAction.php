<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\app\http\requests\Request;

class CreatePrucherAction
{
    public static function execute(): Prucher
    {
        $request = new Request();
        $params = $request->body;
        $prucher = new Prucher();
        $prucher->setQuantity((float) ($params['quantity'] ?? 0));
        $supplier = new Supplier();
        $supplier->setId($params['supplier_id'] ?? '');
        $prucher->setSupplier($supplier);
        $product = new Product();
        $product->setId($params['product_id'] ?? '');
        $prucher->setProduct($product);
        $repo = new PrucherRepository();
        $repo->create($prucher);
        return $prucher;
    }
}
