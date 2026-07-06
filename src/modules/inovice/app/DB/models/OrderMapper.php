<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Order;

class OrderMapper
{
    private array $identityMap = [];
    private PrucherMapper $prucherMapper;
    public function __construct(private PDO $db) {
        $this->prucherMapper = new PrucherMapper($db);
    }

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM inovice.orders WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $order = new Order();
            $order->setId($row['id']);
            $prucher = $this->prucherMapper->findOne($row['prucher_id']);            
            $order->setPrucher($prucher);
            $order->setCreatedAt($row['created_at']);
            $this->identityMap[$id] = $order;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM inovice.orders");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $order = new Order();
                $order->setId($row['id']);
                $prucher = $this->prucherMapper->findOne($row['prucher_id']);               
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
            $stmt = $this->db->prepare("INSERT INTO inovice.orders(prucher_id, created_at) VALUES(?, ?) RETURNING id");
            $stmt->execute([$order->getPrucher()->getId(), $order->getCreatedAt()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $order->setId($id);
            $this->identityMap[$id] = $order;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Order $order)
    {
        $stmt = $this->db->prepare("UPDATE inovice.orders SET prucher_id = ? WHERE id = ?");
        $stmt->execute([$order->getPrucher()->getId(), $order->getId()]);
        $this->identityMap[$order->getId()] = $order;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM inovice.orders WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
