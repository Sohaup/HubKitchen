<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetProductCollectionAction
{
    public static function execute()
    {
        $repo = new ProductRepository();
        $products = $repo->findAll();
        return SerializeToSerin::serializeCollection($products);
    }
}
