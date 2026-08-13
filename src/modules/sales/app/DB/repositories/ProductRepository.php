<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\ProductMapper;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\shared\templates\DB_Trait;

class ProductRepository
{
    private ProductMapper $productMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->productMapper = new ProductMapper($this->dataBase);
    }

    public function findOne(string $id)
    {
        return $this->productMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->productMapper->findAll();
    }

    public function create(Product $product)
    {
        $this->productMapper->create($product);
    }

    public function update(Product $product)
    {
        $this->productMapper->update($product);
    }

    public function delete(string $id)
    {
        $this->productMapper->delete($id);
    }
}
