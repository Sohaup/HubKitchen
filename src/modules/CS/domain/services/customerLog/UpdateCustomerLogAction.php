<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateCustomerLogAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new CustomerLogRepository();
        $log = $repo->findOne($id);
        if (!$log) throw new \Exception('log not found');
        if (isset($params['log_type'])) $log->setLogType($params['log_type']);
        if (isset($params['created_at'])) $log->setCreatedAt($params['created_at']);
        if (isset($params['customer_id'])) {
            $customerRepo = new \PostApi\modules\CS\app\DB\repositories\CustomerRepository();
            $customer = $customerRepo->findOne($params['customer_id']);
            $log->setCustomer($customer);
        }
        $repo->update($log);
        return $log;
    }
}
