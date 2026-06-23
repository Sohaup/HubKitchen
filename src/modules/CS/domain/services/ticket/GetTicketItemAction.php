<?php

namespace PostApi\modules\CS\domain\services\ticket;

use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTicketItemAction
{
    public static function execute(string $id)
    {
        $repo = new TicketRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
