<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
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
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = [])
    {
        $query = "SELECT * FROM sales.categories";
        $whereClauses = [];
        $bindings = [];

        if (isset($criteria['name'])) {
            $whereClauses[] = "name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%";
        }

        if (isset($criteria['image'])) {
            $whereClauses[] = "image LIKE ?";
            $bindings[] = "%" . $criteria['image'] . "%";
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $categoriesRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($categoriesRawData as $categoryRawData) {
                $category = new Category();
                $category->setId($categoryRawData['id']);
                $category->setName($categoryRawData['name']);
                $category->setImage($categoryRawData['image']);
                $category->setCreatedAt($categoryRawData['created_at']);
                $this->identityMap[$categoryRawData['id']] = $category;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Category $category)
    {
        try {
            $createCategoryQuery = $this->db->prepare("INSERT INTO sales.categories(name , image) VALUES(? , ?) RETURNING id, created_at");
            $createCategoryQuery->execute([$category->getName(), $category->getImage()]);
            $res = $createCategoryQuery->fetch(PDO::FETCH_ASSOC);
            $category->setId($res['id']);
            $category->setCreatedAt($res['created_at']);
            $this->identityMap[$category->getId()] = $category;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Category $category)
    {
        try {
            $updateCategoryQuery = $this->db->prepare("UPDATE sales.categories SET name = ? , image = ? WHERE id = ?");
            $updateCategoryQuery->execute([$category->getName(), $category->getImage(), $category->getId()]);
            $this->identityMap[$category->getId()] = $category;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $deleteCategoryQuery = $this->db->prepare("DELETE FROM sales.categories WHERE id = ?");
            $deleteCategoryQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
