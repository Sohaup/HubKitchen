<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\modules\sales\helpers\adapters\stripe\StripeProduct;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateProductAction
{
    public static function execute(Product $product)
    {
        $productRepository = new ProductRepository();
        $stripe = new StripeProduct();
        $stripeProduct = $stripe->create($product);
        $product->setStripeId($stripeProduct->id);
        $image = Files::storeFile('image');
        $product->setImage($image);
        $productRepository->create($product);
        $serin = SerializeToSerin::serialize($product);
        return $serin;
    }
}
