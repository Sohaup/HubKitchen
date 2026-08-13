<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Order;
use PostApi\modules\inovice\domain\entities\Prucher;
use PostApi\modules\inovice\domain\entities\Product;
use PostApi\modules\inovice\domain\entities\Supplier;

class OrderMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id): Order | null
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare(
                "SELECT * FROM inovice.order_view WHERE id = ?"
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;

          
            $prucher = new Prucher();
            $prucher->setId($row['prucher_id']);
            $prucher->setQuantity($row['prucher_quantity']);
            $prucher->setCreatedAt($row['prucher_created_at']);
            $supplier = new Supplier();
            $supplier->setId($row['supplier_id']);
            $supplier->setName($row['supplier_name']);
          
            $product = new Product();
            $product->setId($row['product_id']);
            $product->setName($row['product_name']);
            $product->setPrice($row['product_price']);
            $product->setQuantity($row['product_quantity']);
            $product->setSupplier($supplier);
            $product->setImage($row['product_image']);
            $product->setCreatedAt($row['product_created_at']);
            $prucher->setProduct($product);
            $prucher->setSupplier($supplier);
         
            $order = new Order();
            $order->setId($row['id']);
            $order->setPrucher($prucher);
            $order->setCreatedAt($row['created_at']);

            $this->identityMap[$id] = $order;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM inovice.order_view"
        );
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                
                $prucher = new Prucher();
                $prucher->setId($row['prucher_id']);
                $prucher->setQuantity($row['prucher_quantity']);
                $prucher->setCreatedAt($row['prucher_created_at']);
                $supplier = new Supplier();
                $supplier->setId($row['supplier_id']);
                $supplier->setName($row['supplier_name']);
                
                $product = new Product();
                $product->setId($row['product_id']);
                $product->setName($row['product_name']);
                $product->setPrice($row['product_price']);
                $product->setQuantity($row['product_quantity']);
                $product->setSupplier($supplier);
                $product->setImage($row['product_image']);
                $product->setCreatedAt($row['product_created_at']);
                // Set the fetched Supplier (assuming it's already created or can be fetched)

                // Set the fetched Product
                $prucher->setProduct($product);

                // Set the fetched Supplier
                $prucher->setSupplier($supplier);

                // Create and set the Order
                $order = new Order();
                $order->setId($row['id']);
                $order->setPrucher($prucher);
                $order->setCreatedAt($row['created_at']);

                $this->identityMap[$row['id']] = $order;
            }
        }

        return $this->identityMap;
    }

    public function insert(Order $order)
    {
        try {

            $stmtOrder = $this->db->prepare("INSERT INTO inovice.orders(prucher_id, created_at) VALUES(?, ?) RETURNING id");
            $stmtOrder->execute([$order->getPrucher()->getId(), $order->getCreatedAt()]);
            $orderId = $stmtOrder->fetch(PDO::FETCH_ASSOC)['id'];
            $order->setId($orderId);
            $this->identityMap[$orderId] = $order;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Order $order)
    {
        try {

            // Update the order
            $stmtOrderUpdate = $this->db->prepare("UPDATE inovice.orders SET prucher_id = ?, created_at = ? WHERE id = ?");
            $stmtOrderUpdate->execute([$order->getPrucher()->getId(), $order->getCreatedAt(), $order->getId()]);
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function delete(string $id)
    {
        try {
            // Delete the order
            $stmtOrder = $this->db->prepare("DELETE FROM inovice.orders WHERE id = ?");
            $stmtOrder->execute([$id]);

            unset($this->identityMap[$id]);
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
}
