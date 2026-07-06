<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\app\http\requests\Request;

class UpdateProductAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new ProductRepository();
        $product = $repo->findOne($id);
        if (!$product) {
            throw new \Exception("product not found");
        }
        if (isset($params['name'])) {
            $product->setName($params['name']);
        }
        if (isset($params['price'])) {
            $product->setPrice((float) $params['price']);
        }
        if (isset($params['quantity'])) {
            $product->setQuantity((int) $params['quantity']);
        }
        if (isset($params['supplier_id'])) {
            $supplier = new Supplier();
            $supplier->setId($params['supplier_id']);
            $product->setSupplier($supplier);
        }
        $repo->update($product);
        return $product;
    }
}
