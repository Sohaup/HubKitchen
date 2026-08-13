<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetProductCollectionAction
{
    public static function execute()
    {
        $productRepository = new ProductRepository();
        $products = $productRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($products);
        return $serin;
    }
}
