<?php

namespace PostApi\modules\inovice\domain\services\product;

use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;

class UpdateProductAction
{
    public static function execute(string $id, array $params)
    {
        $repo = new ProductRepository();
        $product = $repo->findOne($id);
        $queue = new TaskQueue();
        if (!$product) {
            throw new \Exception("product not found");
        }
        if (isset($params['name'])) {
            $product->setName($params['name']);
        }
        if (isset($params['price'])) {
            $product->setPrice((float) $params['price']);
        }
        if (isset($params['quantity'])) {
            $product->setQuantity((int) $params['quantity']);
        }
        if (isset($params['supplier_id'])) {
            $supplier = new Supplier();
            $supplier->setId($params['supplier_id']);
            $product->setSupplier($supplier);
        }
        if (isset($params['image'])) {
            $queue->push(new ClousreCommand(function () use ($product) {
                Retery::execute(function () use ($product) {
                    Files::deleteFile($product->getImage());
                });
            }));
            $queue->push(new ClousreCommand(function () {
                $imagePath = Retery::execute(function () {
                    $newImage = Files::storeFile('image');
                    return $newImage;
                });
                return $imagePath;
            }));
            $results = $queue->execute();
            $product->setImage($results[1]);
        }
        $repo->update($product);
        return $product;
    }
}
