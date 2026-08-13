<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Category;

class CategoryMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getCategoryQuery = $this->db->prepare("SELECT * FROM sales.categories WHERE id = ?");
        $getCategoryQuery->execute([$id]);
        $categoryRawData = $getCategoryQuery->fetch(PDO::FETCH_ASSOC);
        if ($categoryRawData) {
            $category = new Category();
            $category->setId($categoryRawData['id']);
            $category->setName($categoryRawData['name']);
            $category->setImage($categoryRawData['image']);
            $category->setCreatedAt($categoryRawData['created_at']);
            $this->identityMap[$categoryRawData['id']] = $category;
            return $category;
        }
    }

    public function findAll()
    {
        $getCategoriesQuery = $this->db->prepare("SELECT * FROM sales.categories ");
        $getCategoriesQuery->execute([]);
        $categoriesRawData = $getCategoriesQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categoriesRawData as $categoryRawData) {
            if (!isset($this->identityMap[$categoryRawData['id']])) {
                $category = new Category();
                $category->setId($categoryRawData['id']);
                $category->setName($categoryRawData['name']);
                $category->setImage($categoryRawData['image']);
                $category->setCreatedAt($categoryRawData['created_at']);
                $this->identityMap[$categoryRawData['id']] = $category;
            }
        }
        return $this->identityMap;
    }

    public function create(Category $category)
    {
        $createCategoryQuery = $this->db->prepare("INSERT INTO sales.categories(name , image) VALUES(? , ?) RETURNING id, created_at");
        $createCategoryQuery->execute([$category->getName() ,$category->getImage()]);
        $res = $createCategoryQuery->fetch(PDO::FETCH_ASSOC);
        $category->setId($res['id']);
        $category->setCreatedAt($res['created_at']);
        $this->identityMap[$category->getId()] = $category;
    }

    public function update(Category $category)
    {
        $updateCategoryQuery = $this->db->prepare("UPDATE sales.categories SET name = ? , image = ? WHERE id = ?");
        $updateCategoryQuery->execute([$category->getName() , $category->getImage(), $category->getId()]);
        $this->identityMap[$category->getId()] = $category;
    }

    public function delete(int $id)
    {
        $deleteCategoryQuery = $this->db->prepare("DELETE FROM sales.categories WHERE id = ?");
        $deleteCategoryQuery->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
