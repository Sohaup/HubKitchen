<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\modules\inovice\domain\entities\Product;

class UpdatePrucherAction
{
    public static function execute(string $id , array $params)
    {        
        $repo = new PrucherRepository();
        $prucher = $repo->findOne($id);
        if (!$prucher) {
            throw new \Exception("prucher not found");
        }
       
        if (isset($params['quantity'])) {
            $prucher->setQuantity((float) $params['quantity']);
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
