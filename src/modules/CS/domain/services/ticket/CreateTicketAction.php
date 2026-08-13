<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\modules\CS\domain\entities\Ticket;

class CreateTicketAction
{
    public static function execute(array $params): Ticket
    {        
        $repo = new TicketRepository();
        $entity = new Ticket();
        $entity->setType($params['type']);
        $repo->create($entity);
        return $entity;
    }
}
