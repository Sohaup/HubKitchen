<?php

namespace PostApi\modules\inovice\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\inovice\domain\entities\Supplier;

class SupplierMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM inovice.suppliers WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return null;
            $supplier = new Supplier();
            $supplier->setId($row['id']);
            $supplier->setName($row['name']);
            $this->identityMap[$id] = $supplier;
        }
        return $this->identityMap[$id];
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM inovice.suppliers");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $supplier = new Supplier();
                $supplier->setId($row['id']);
                $supplier->setName($row['name']);
                $this->identityMap[$row['id']] = $supplier;
            }
        }
        return $this->identityMap;
    }

    public function insert(Supplier $supplier)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO inovice.suppliers(name) VALUES(?) RETURNING id");
            $stmt->execute([$supplier->getName()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $supplier->setId($id);
            $this->identityMap[$id] = $supplier;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(Supplier $supplier)
    {
        $stmt = $this->db->prepare("UPDATE inovice.suppliers SET name = ? WHERE id = ?");
        $stmt->execute([$supplier->getName(), $supplier->getId()]);
        $this->identityMap[$supplier->getId()] = $supplier;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM inovice.suppliers WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
