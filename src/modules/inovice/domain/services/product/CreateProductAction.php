<?php

namespace PostApi\modules\inovice\domain\services\product;

use Override;
use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;
use PostApi\modules\inovice\domain\entityListeners\CreateStockProductListener;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateProductAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $createStockEvent = "";
    private Product $stock;
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new CreateStockProductListener());
    }
    public function execute(array $params): Product
    {
        $queue = new TaskQueue();         
        $product = new Product();
        $product->setName($params['name'] ?? '');
        $product->setPrice((float) ($params['price'] ?? 0));
        $product->setQuantity((int) ($params['quantity'] ?? 0));
        $supplier = new Supplier();
        $supplier->setId($params['supplier_id'] ?? '');
        $product->setSupplier($supplier);
        $queue->push(new ClousreCommand(function () {
            $image = Retery::execute(function () {
                $image = Files::storeFile('image');
                return $image;
            });
            return $image;
        }));
        $results = $queue->execute();
        $product->setImage($results[0]);
        $repo = new ProductRepository();
        $repo->create($product);
        $this->createStockEvent = "created";
        $this->stock = $product;
        $this->notify();
        return $product;
    }

    #[Override]
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    #[Override]
    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }
    #[Override]
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    public function getEvent() {
        return $this->createStockEvent;
    } 
    public function getStock() {
        return $this->stock;
    }
}
