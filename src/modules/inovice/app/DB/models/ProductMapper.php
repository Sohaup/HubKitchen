<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;

class ProductMapper
{
    private array $identityMap = [];
    
    public function __construct(private PDO $db) {}

    public function findOne(string $id): Product | null
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare(
                "SELECT 
                    p.*,
                    s.id AS supplier_id,
                    s.name AS supplier_name
                FROM inovice.products AS p 
                LEFT JOIN inovice.suppliers AS s ON s.id = p.supplier_id
                WHERE p.id = ?
                "
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;

            $product = new Product();
            $product->setId($row['id']);
            $product->setName($row['name']);
            $product->setPrice((float)$row['price']);
            $product->setQuantity((int)$row['quantity']);
            $supplier = new Supplier();
            $supplier->setId($row['supplier_id']);
            $supplier->setName($row['supplier_name']);
            $product->setSupplier($supplier);
            $product->setImage($row['image']);
            $product->setCreatedAt($row['created_at']);

            $this->identityMap[$id] = $product;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare(
            "SELECT 
                    p.*,
                    s.id AS supplier_id,
                    s.name AS supplier_name
                FROM inovice.products AS p 
                LEFT JOIN inovice.suppliers AS s ON s.id = p.supplier_id"
        );
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $supplier = new Supplier();
                $supplier->setId($row['supplier_id']);
                $supplier->setName($row['supplier_name']);
               
                
                $product = new Product();
                $product->setId($row['id']);
                $product->setName($row['name']);
                $product->setPrice((float)$row['price']);
                $product->setQuantity((int)$row['quantity']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['created_at']);
                $product->setSupplier($supplier);

                $this->identityMap[$row['id']] = $product;
            }
        }
        return $this->identityMap;
    }

    public function findBy(array $criteria = []): array
    {
        $query = "
        SELECT p.*, s.id AS supplier_id, s.name AS supplier_name, s.created_at AS supplier_created_at
        FROM inovice.products p
        LEFT JOIN inovice.suppliers s ON p.supplier_id = s.id
    ";

        $whereClauses = [];
        $bindings = [];
       
        if (!empty($criteria['supplier_id'])) {
            $whereClauses[] = "s.id = ?";
            $bindings[] = $criteria['supplier_id'];
        }

        if (!empty($criteria['name'])) {
            $whereClauses[] = "p.name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%"; 
        }

        if (!empty($criteria['price'])) {
            $whereClauses[] = "p.price = ?";
            $bindings[] = $criteria['price'];
        }

        if (!empty($criteria['quantity'])) {
            $whereClauses[] = "p.quantity = ?";
            $bindings[] = $criteria['quantity'];
        }
       
        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($bindings);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($rows as $row) {
            $id = $row['id'];
           
            if (!isset($this->identityMap[$id])) {
                $supplier = new Supplier();
                $supplier->setId($row['supplier_id']);
                $supplier->setName($row['supplier_name']);
                
                
                $product = new Product();
                $product->setId($row['id']);
                $product->setName($row['name']);
                $product->setPrice((float)$row['price']);
                $product->setQuantity((int)$row['quantity']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['created_at']);
                $product->setSupplier($supplier);

                $this->identityMap[$id] = $product;
            }

            $results[] = $this->identityMap[$id];
        }

        return $results;
    }

    public function insert(Product $product)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO inovice.products(name, price, quantity, supplier_id, image) VALUES(?, ?, ?, ? , ?) RETURNING id");
            $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity(), $product->getSupplier()->getId(), $product->getImage()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $product->setId($id);
            $this->identityMap[$id] = $product;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Product $product)
    {
        $stmt = $this->db->prepare("UPDATE inovice.products SET name = ?, price = ?, quantity = ?, supplier_id = ? , image = ? WHERE id = ?");
        $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity(), $product->getSupplier()->getId(), $product->getImage(), $product->getId()]);
        $this->identityMap[$product->getId()] = $product;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM inovice.products WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
