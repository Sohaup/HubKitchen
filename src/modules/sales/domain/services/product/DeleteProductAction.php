<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\helpers\adapters\stripe\StripeProduct;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;

class DeleteProductAction
{
    public static function execute(string $id)
    {
        $productRepository = new ProductRepository();
        $stripe = new StripeProduct();
        $product = $productRepository->findOne($id);    
        $queue = new TaskQueue();   
        if ($product) {
            $productRepository->delete($id);
            $stripe->delete($product->getStripeId());
            Files::deleteFile($product->getImage());
        }
    }
}
