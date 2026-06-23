<?php
namespace PostApi\modules\CS\app\DB\repositories;

use PostApi\modules\CS\app\DB\models\TicketMapper;
use PostApi\modules\CS\domain\entities\Ticket;
use PostApi\shared\templates\DB_Trait;

class TicketRepository
{
    use DB_Trait;
    private TicketMapper $ticketMapper;
    public function __construct()
    {
        $this->initialize();
        $this->ticketMapper = new TicketMapper($this->postgre->pdo);
    }

    public function findOne(string $id) {
        return $this->ticketMapper->findOne($id);
    }

    public function findAll() {
        return $this->ticketMapper->findAll();
    }

    public function create(Ticket $ticket) {
        $this->ticketMapper->insert($ticket);
    }

    public function update(Ticket $ticket) {
        $this->ticketMapper->update($ticket);
    }

    public function delete(string $id) {
        $this->ticketMapper->delete($id);
    }
}
