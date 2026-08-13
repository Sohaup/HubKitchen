<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetProductItemAction
{
    public static function execute(string $id)
    {
        $productRepository = new ProductRepository();
        $product = $productRepository->findOne($id);
        $serin = SerializeToSerin::serialize($product);
        return $serin;
    }
}
