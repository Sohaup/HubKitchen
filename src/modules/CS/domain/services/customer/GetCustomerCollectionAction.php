<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerCollectionAction
{
    public static function execute(array $customers = null)
    {
        if ($customers === null) {
            $customersRepo = new CustomerRepository();
            $customers = $customersRepo->findAll();
        }
        $serin = SerializeToSerin::serializeCollection($customers);
        return $serin;
    }
}
