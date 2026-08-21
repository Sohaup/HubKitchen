<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\CategoryMapper;
use PostApi\modules\sales\domain\entities\Category;
use PostApi\shared\templates\DB_Trait;

class CategoryRepository
{
    private CategoryMapper $categoryMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->categoryMapper = new CategoryMapper($this->dataBase);
    }

    public function findOne(int $id)
    {
        return $this->categoryMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->categoryMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->categoryMapper->findBy($critiria);
    }

    public function create(Category $category)
    {
        $this->categoryMapper->create($category);
    }

    public function update(Category $category)
    {
        $this->categoryMapper->update($category);
    }

    public function delete(int $id)
    {
        $this->categoryMapper->delete($id);
    }
}
