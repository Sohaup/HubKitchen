<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;

class GetCustomerLogAction
{
    public static function execute(int $id)
    {
        $repo = new CustomerLogRepository();
        return $repo->findOne($id);
    }
}
