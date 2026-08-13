<?php

namespace PostApi\modules\inovice\domain\services\product;

use Override;
use PostApi\modules\inovice\app\DB\repositories\ProductRepository;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entityListeners\DeleteStockProductListener;
use PostApi\shared\helpers\fecade\Files;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class DeleteProductAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $deleteStockState = "";
    private Product $stock;
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new DeleteStockProductListener());
    }
    public function execute(string $id)
    {
        $repo = new ProductRepository();
        $product = $repo->findOne($id);
        $image = $product->getImage();
        $repo->delete($id);
        Files::deleteFile($image);
        $this->stock = $product;
        $this->deleteStockState = "deleted";
        $this->notify();
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
        foreach($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getStock() {
        return $this->stock;
    }
    public function getEvent() {
        return $this->deleteStockState;
    }
}
