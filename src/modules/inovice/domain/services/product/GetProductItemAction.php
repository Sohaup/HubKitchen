<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetProductItemAction
{
    public static function execute(string $id)
    {
        $repo = new ProductRepository();
        $product = $repo->findOne($id);
        return SerializeToSerin::serialize($product);
    }
}
