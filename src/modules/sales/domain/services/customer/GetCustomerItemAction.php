<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerItemAction
{
    public static function execute(string $id)
    {
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findOne($id);
        $serin = SerializeToSerin::serialize($customer);
        return $serin;
    }
}
