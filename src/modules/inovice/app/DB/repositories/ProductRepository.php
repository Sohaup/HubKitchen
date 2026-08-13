<?php
namespace PostApi\modules\inovice\app\DB\repositories;

use PostApi\modules\inovice\app\DB\models\ProductMapper;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\shared\templates\DB_Trait;

class ProductRepository
{
    use DB_Trait;
    private ProductMapper $productMapper;
    public function __construct()
    {
        $this->initialize();
        $this->productMapper = new ProductMapper($this->dataBase);
    }

    public function findOne(string $id) {
        return $this->productMapper->findOne($id);
    }

    public function findAll() {
        return $this->productMapper->findAll();
    }

    public function findBy(array $critirias) {
        return $this->productMapper->findBy($critirias);
    }

    public function create(Product $product) {
        $this->productMapper->insert($product);
    }

    public function update(Product $product) {
        $this->productMapper->update($product);
    }

    public function delete(string $id) {
        $this->productMapper->delete($id);
    }
}
