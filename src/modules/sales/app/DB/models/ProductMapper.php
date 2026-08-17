<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\domain\entities\Category;
use PostApi\modules\sales\domain\entities\Product;

class ProductMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getProductQuery = $this->db->prepare("SELECT p.* , c.name AS category_name , c.image AS category_image , c.created_at AS category_created_at FROM sales.products p LEFT JOIN sales.categories c ON p.category_id = c.id WHERE p.id = ?");
            $getProductQuery->execute([$id]);
            $productRawData = $getProductQuery->fetch(PDO::FETCH_ASSOC);
            if ($productRawData) {
                $product = new Product();
                $product->setId($productRawData['id']);
                $product->setName($productRawData['name']);
                $product->setPrice($productRawData['price']);
                $product->setStripeId($productRawData['stripe_id']);
                $product->setImage($productRawData['image']);
                $product->setProps($productRawData['props']);
                $product->setCreatedAt($productRawData['created_at']);
                $category = new Category();
                $category->setId($productRawData['category_id']);
                $category->setName($productRawData['category_name']);
                $category->setImage($productRawData['category_image']);
                $category->setCreatedAt($productRawData['category_created_at']);
                $product->setCategory($category);
                $this->identityMap[$productRawData['id']] = $product;
                return $product;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getProductsQuery = $this->db->prepare("SELECT p.* , c.name AS category_name , c.image AS category_image , c.created_at AS category_created_at FROM sales.products p LEFT JOIN sales.categories c ON p.category_id = c.id");
            $getProductsQuery->execute([]);
            $productsRawData = $getProductsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($productsRawData as $productRawData) {
                if (!isset($this->identityMap[$productRawData['id']])) {
                    $product = new Product();
                    $product->setId($productRawData['id']);
                    $product->setName($productRawData['name']);
                    $product->setPrice($productRawData['price']);
                    $product->setStripeId($productRawData['stripe_id']);
                    $product->setImage($productRawData['image']);
                    $product->setProps($productRawData['props']);
                    $product->setCreatedAt($productRawData['created_at']);
                    $category = new Category();
                    $category->setId($productRawData['category_id']);
                    $category->setName($productRawData['category_name']);
                    $category->setImage($productRawData['category_image']);
                    $category->setCreatedAt($productRawData['category_created_at']);
                    $product->setCategory($category);
                    $this->identityMap[$productRawData['id']] = $product;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Product $product)
    {
        try {
            $createProductQuery = $this->db->prepare("INSERT INTO sales.products(name, price , stripe_id , image , props , category_id) VALUES(?, ? , ? , ? , ? , ?) RETURNING id, created_at");
            $createProductQuery->execute([$product->getName(), $product->getPrice(), $product->getStripeId() ?? null, $product->getImage(), $product->getProps(), $product->getCategory()->getId()]);
            $res = $createProductQuery->fetch(PDO::FETCH_ASSOC);
            $product->setId($res['id']);
            $product->setCreatedAt($res['created_at']);
            $this->identityMap[$product->getId()] = $product;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Product $product)
    {
        try {
            $updateProductQuery = $this->db->prepare("UPDATE sales.products SET name = ?, price = ? image = ? , props = ? , category_id = ? WHERE id = ?");
            $updateProductQuery->execute([$product->getName(), $product->getPrice(), $product->getImage(), $product->getProps(), $product->getCategory()->getId(), $product->getId()]);
            $this->identityMap[$product->getId()] = $product;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $deleteProductQuery = $this->db->prepare("DELETE FROM sales.products WHERE id = ?");
            $deleteProductQuery->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
