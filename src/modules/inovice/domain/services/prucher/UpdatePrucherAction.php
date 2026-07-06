<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\app\http\requests\Request;

class UpdatePrucherAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new PrucherRepository();
        $prucher = $repo->findOne($id);
        if (!$prucher) {
            throw new \Exception("prucher not found");
        }
        if (isset($params['quantity'])) {
            $prucher->setQuantity((float) $params['quantity']);
        }
        if (isset($params['supplier_id'])) {
            $supplier = new Supplier();
            $supplier->setId($params['supplier_id']);
            $prucher->setSupplier($supplier);
        }
        if (isset($params['product_id'])) {
            $product = new Product();
            $product->setId($params['product_id']);
            $prucher->setProduct($product);
        }
        $repo->update($prucher);
        return $prucher;
    }
}
