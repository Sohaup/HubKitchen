<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerItemAction
{
    public static function execute(string $id)
    {
        $customerRepo = new CustomerRepository();
        $customer = $customerRepo->findOne($id);
        $serin = SerializeToSerin::serialize($customer);
        return $serin;
    }
}
