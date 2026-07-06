<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Prucher;

class PrucherMapper
{
    private array $identityMap = [];
    private SupplierMapper $supplierMapper;
    private ProductMapper $productMapper;
    public function __construct(private PDO $db)
    {
        $this->supplierMapper = new SupplierMapper($db);
        $this->productMapper = new ProductMapper($db);
    }

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM inovice.pruchers WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $prucher = new Prucher();
            $prucher->setId($row['id']);
            $prucher->setQuantity((float) $row['quantity']);
            $supplier = $this->supplierMapper->findOne($row['supplier_id']);
            $prucher->setSupplier($supplier);
            $product = $this->productMapper->findOne($row['product_id']);
            $prucher->setProduct($product);
            $prucher->setCreatedAt($row['created_at']);
            $this->identityMap[$id] = $prucher;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM inovice.pruchers");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $prucher = new Prucher();
                $prucher->setId($row['id']);
                $prucher->setQuantity((float) $row['quantity']);
                $supplier = $this->supplierMapper->findOne($row['supplier_id']);
                $prucher->setSupplier($supplier);
                $product = $this->productMapper->findOne($row['product_id']);
                $prucher->setProduct($product);
                $prucher->setCreatedAt($row['created_at']);
                $this->identityMap[$row['id']] = $prucher;
            }
        }
        return $this->identityMap;
    }

    public function insert(Prucher $prucher)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO inovice.pruchers(quantity, supplier_id, product_id) VALUES(?, ?, ?) RETURNING id");
            $stmt->execute([$prucher->getQuantity(), $prucher->getSupplier()->getId(), $prucher->getProduct()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $prucher->setId($id);
            $this->identityMap[$id] = $prucher;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Prucher $prucher)
    {
        $stmt = $this->db->prepare("UPDATE inovice.pruchers SET quantity = ?, supplier_id = ?, product_id = ? WHERE id = ?");
        $stmt->execute([$prucher->getQuantity(), $prucher->getSupplier()->getId(), $prucher->getProduct()->getId(), $prucher->getId()]);
        $this->identityMap[$prucher->getId()] = $prucher;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM inovice.pruchers WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
