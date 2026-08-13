<?php

namespace PostApi\modules\inovice\domain\entityListeners;

use Override;
use PostApi\modules\inovice\domain\services\product\CreateProductAction;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\modules\sales\helpers\adapters\stripe\StripeProduct;
use SplObserver;
use SplSubject;

class CreateStockProductListener implements SplObserver
{
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateProductAction && $subject->getEvent() == "created") {
            $productRepo = new ProductRepository();
            $stripe = new StripeProduct();
            $stock = $subject->getStock();
            $product = new Product();
            $product->setName($stock->getName());
            $product->setPrice($stock->getPrice());
            $product->setImage($stock->getImage());
            $stripeProduct = $stripe->create($product);
            $product->setStripeId($stripeProduct->id);
            $productRepo->create($product);
        }
    }
}
