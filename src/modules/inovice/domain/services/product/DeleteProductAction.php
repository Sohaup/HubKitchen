<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;

class DeleteProductAction
{
    public static function execute(string $id)
    {
        $repo = new ProductRepository();
        $repo->delete($id);
    }
}
