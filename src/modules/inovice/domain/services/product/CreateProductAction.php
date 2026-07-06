<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\app\http\requests\Request;

class CreateProductAction
{
    public static function execute(): Product
    {
        $request = new Request();
        $params = $request->body;
        $product = new Product();
        $product->setName($params['name'] ?? '');
        $product->setPrice((float) ($params['price'] ?? 0));
        $product->setQuantity((int) ($params['quantity'] ?? 0));
        $supplier = new Supplier();
        $supplier->setId($params['supplier_id'] ?? '');
        $product->setSupplier($supplier);
        $repo = new ProductRepository();
        $repo->create($product);
        return $product;
    }
}
