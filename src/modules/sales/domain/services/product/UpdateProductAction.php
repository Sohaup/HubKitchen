<?php

namespace PostApi\modules\sales\domain\services\product;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\modules\sales\helpers\adapters\stripe\StripeProduct;
use PostApi\shared\helpers\fecade\Files;

class UpdateProductAction
{
    public static function execute(Product $product, bool $isFile, array $params)
    {       
        $productRepository = new ProductRepository();
        $stripe = new StripeProduct();
        if (isset($params['name'])) {
            $product->setName($params['name']);
        }
        if (isset($params['price'])) {
            $product->setName($params['price']);
        }
        if (isset($params['quantity'])) {
            $product->setName($params['quantity']);
        }
        if (isset($params['category_id'])) {
            $categoryRepo = new CategoryRepository();
            $category = $categoryRepo->findOne($params['catgory_id']);
            $product->setCategory($category);
        }
        if ($isFile) {
            Files::deleteFile($product->getImage());
            $image = Files::storeFile('image');
            $product->setImage($image);
        }
        $stripe->update($product);
        $productRepository->update($product);
    }
}
