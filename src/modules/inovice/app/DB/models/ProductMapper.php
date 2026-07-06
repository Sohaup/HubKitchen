<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;

class ProductMapper
{
    private array $identityMap = [];
    private SupplierMapper $supplierMapper;
    public function __construct(private PDO $db) {
        $this->supplierMapper = new SupplierMapper($db);
    }

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM inovice.products WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $product = new Product();
            $product->setId($row['id']);
            $product->setName($row['name']);
            $product->setPrice((float) $row['price']);
            $product->setQuantity((int) $row['quantity']);
            $supplier = $this->supplierMapper->findOne($row['supplier_id']); 
            $product->setSupplier($supplier);
            $product->setCreatedAt($row['created_at']);
            $this->identityMap[$id] = $product;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM inovice.products");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $product = new Product();
                $product->setId($row['id']);
                $product->setName($row['name']);
                $product->setPrice((float) $row['price']);
                $product->setQuantity((int) $row['quantity']);
                $supplier = $this->supplierMapper->findOne($row['supplier_id']);               
                $product->setSupplier($supplier);
                $product->setCreatedAt($row['created_at']);
                $this->identityMap[$row['id']] = $product;
            }
        }
        return $this->identityMap;
    }

    public function insert(Product $product)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO inovice.products(name, price, quantity, supplier_id) VALUES(?, ?, ?, ?) RETURNING id");
            $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity(), $product->getSupplier()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $product->setId($id);
            $this->identityMap[$id] = $product;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Product $product)
    {
        $stmt = $this->db->prepare("UPDATE inovice.products SET name = ?, price = ?, quantity = ?, supplier_id = ? WHERE id = ?");
        $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity(), $product->getSupplier()->getId(), $product->getId()]);
        $this->identityMap[$product->getId()] = $product;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM inovice.products WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
