<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;

class DeleteTicketAction
{
    public static function execute(string $id)
    {
        $repo = new TicketRepository();
        $repo->delete($id);
    }
}
