<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTicketCollectionAction
{
    public static function execute()
    {
        $repo = new TicketRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
