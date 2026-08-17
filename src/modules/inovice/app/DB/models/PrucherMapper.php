<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use DateTime;
use Error;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\modules\inovice\domain\entities\Supplier;

class PrucherMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare(
                    "SELECT * FROM inovice.prucher_view WHERE prucher_id = ?"
                );
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;

                $prucher = new Prucher();
                $prucher->setId($row['prucher_id']);
                $prucher->setQuantity($row['quantity']);
                $prucher->setCreatedAt($row['prucher_created_at']);

                $supplier = new Supplier();
                $supplier->setId($row['supplier_id']);
                $supplier->setName($row['supplier_name']);
                $prucher->setSupplier($supplier);

                $product = new Product();
                $product->setId($row['product_id']);
                $product->setName($row['product_name']);
                $product->setPrice($row['price']);
                $product->setQuantity($row['product_quantity']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['product_created_at']);
                $product->setSupplier($supplier);
                $prucher->setProduct($product);

                $this->identityMap[$id] = $prucher;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM inovice.prucher_view"
            );
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['prucher_id']])) {
                    $prucher = new Prucher();
                    $prucher->setId($row['prucher_id']);
                    $prucher->setQuantity($row['quantity']);
                    $prucher->setCreatedAt($row['prucher_created_at']);

                    $supplier = new Supplier();
                    $supplier->setId($row['supplier_id']);
                    $supplier->setName($row['supplier_name']);
                    $prucher->setSupplier($supplier);

                    $product = new Product();
                    $product->setId($row['product_id']);
                    $product->setName($row['product_name']);
                    $product->setPrice($row['price']);
                    $product->setQuantity($row['product_quantity']);
                    $product->setImage($row['image']);
                    $product->setCreatedAt($row['product_created_at']);
                    $product->setSupplier($supplier);
                    $prucher->setProduct($product);

                    $this->identityMap[$row['prucher_id']] = $prucher;
                }
            }

            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Prucher $prucher)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO inovice.pruchers(quantity, supplier_id, product_id) VALUES(? , ? , ? ) RETURNING id"
            );
            $stmt->execute([$prucher->getQuantity(), $prucher->getSupplier()->getId(), $prucher->getProduct()->getId()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $prucher->setId($id);
            $this->identityMap[$id] = $prucher;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Prucher $prucher)
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE inovice.pruchers SET quantity = ? , supplier_id = ? , product_id = ?  WHERE id = ?"
            );
            $stmt->execute([$prucher->getQuantity(), $prucher->getSupplier()->getId(), $prucher->getProduct()->getId(), $prucher->getId()]);
            $this->identityMap[$prucher->getId()] = $prucher;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM inovice.pruchers WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
