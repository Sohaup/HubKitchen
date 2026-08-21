<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerLogCollectionAction
{
    public static function execute(array $items = null)
    {
        if ($items === null) {
            $repo = new CustomerLogRepository();
            $items = $repo->findAll();
        }
        return SerializeToSerin::serializeCollection($items);
    }
}
