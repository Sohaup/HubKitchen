<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\modules\CS\domain\entities\Ticket;

class UpdateTicketAction
{
    public static function execute(string $id , array $params)
    {        
        $repo = new TicketRepository();
        $entity = new Ticket();
        $entity->setId($id);
        $entity->setType($params['type']);
        $repo->update($entity);
    }
}
