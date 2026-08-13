<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\modules\inovice\app\DB\repositories\SupplierRepository;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;

class CreatePrucherAction
{
    public static function execute(array $params): Prucher
    {
        $repo = new PrucherRepository();
        $supplierRepo = new SupplierRepository();
        $productRepo = new ProductRepository();
        $prucher = new Prucher();
        $prucher->setQuantity((float) ($params['quantity'] ?? 0));
        $product = $productRepo->findOne($params['product_id']);
        $prucher->setProduct($product);
        $supplier = $supplierRepo->findOne($product->getSupplier()->getId());
        $prucher->setSupplier($supplier);
        $repo->create($prucher);
        return $prucher;
    }
}
