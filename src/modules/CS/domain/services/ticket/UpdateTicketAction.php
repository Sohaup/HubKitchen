<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\modules\CS\domain\entities\Ticket;
use PostApi\shared\app\http\requests\Request;

class UpdateTicketAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new TicketRepository();
        $entity = new Ticket();
        $entity->setId($id);
        $entity->setType($params['type']);
        $repo->update($entity);
    }
}
