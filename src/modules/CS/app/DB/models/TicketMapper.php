<?php

namespace PostApi\modules\CS\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Ticket;

class TicketMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT * FROM cs.tickets WHERE id = ?");
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) return null;
                $ticket = new Ticket();
                $ticket->setId($row['id']);
                $ticket->setType($row['type']);
                $this->identityMap[$id] = $ticket;
            }
            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cs.tickets");
            $stmt->execute([]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $ticket = new Ticket();
                    $ticket->setId($row['id']);
                    $ticket->setType($row['type']);
                    $this->identityMap[$row['id']] = $ticket;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "SELECT * FROM cs.tickets";
        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['id'])) {
            $whereClauses[] = "id = ?";
            $bindings[] = $criteria['id'];
        }

        if (!empty($criteria['type'])) {
            $whereClauses[] = "type LIKE ?";
            $bindings[] = "%" . $criteria['type'] . "%";
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $ticket = new Ticket();
                    $ticket->setId($row['id']);
                    $ticket->setType($row['type']);
                    $this->identityMap[$row['id']] = $ticket;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function insert(Ticket $ticket)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.tickets(type) VALUES(?) RETURNING id");
            $stmt->execute([$ticket->getType()]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $ticket->setId($id);
            $this->identityMap[$id] = $ticket;
        } catch (PDOException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Ticket $ticket)
    {
        try {
            $stmt = $this->db->prepare("UPDATE cs.tickets SET type = ? WHERE id = ?");
            $stmt->execute([$ticket->getType(), $ticket->getId()]);
            $this->identityMap[$ticket->getId()] = $ticket;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cs.tickets WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
