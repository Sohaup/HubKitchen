<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerLogItemAction
{
    public static function execute(int $id)
    {
        $repo = new CustomerLogRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
