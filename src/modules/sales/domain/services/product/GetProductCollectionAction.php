<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetProductCollectionAction
{
    public static function execute(array $items = null)
    {
        $productRepository = new ProductRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $productRepository->findAll());
        return $serin;
    }
}
