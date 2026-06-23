<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;

class DeleteCustomerLogAction
{
    public static function execute(int $id)
    {
        $repo = new CustomerLogRepository();
        $log = $repo->findOne($id);
        if ($log) {
            $repo->delete($id);
        }
    }
}
