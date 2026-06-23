<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\Ticket;

class TicketMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
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
    }

    public function findAll()
    {
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
            echo $error->getMessage();
        }
    }

    public function update(Ticket $ticket)
    {
        $stmt = $this->db->prepare("UPDATE cs.tickets SET type = ? WHERE id = ?");
        $stmt->execute([$ticket->getType(), $ticket->getId()]);
        $this->identityMap[$ticket->getId()] = $ticket;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.tickets WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
