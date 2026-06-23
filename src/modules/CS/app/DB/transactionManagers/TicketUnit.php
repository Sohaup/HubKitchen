<?php

namespace PostApi\modules\CS\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\CS\app\DB\models\TicketMapper;
use PostApi\modules\CS\domain\entities\Ticket;

class TicketUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private TicketMapper $ticketMapper, private PDO $db) {}
    public function registerNew(Ticket &$ticket)
    {
        if (!in_array($ticket, $this->newObjects, true)) {
            $this->newObjects[] = $ticket;
        }
    }
    public function registerDirty(Ticket &$ticket)
    {
        if (!in_array($ticket, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $ticket;
        }
    }
    public function registerDeleted(Ticket &$ticket)
    {
        if (!in_array($ticket, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $ticket;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->ticketMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->ticketMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->ticketMapper->delete($entity->getId());
            }
            $this->db->commit();
            $this->newObjects = [];
            $this->dirtyObjects = [];
            $this->deletedObjects = [];
        } catch (PDOException $error) {
            $this->db->rollBack();
            echo $error->getMessage();
        }
    }
}
