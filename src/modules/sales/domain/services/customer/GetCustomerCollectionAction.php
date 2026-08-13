<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerCollectionAction
{
    public static function execute()
    {
        $customerRepository = new CustomerRepository();
        $customers = $customerRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($customers);
        return $serin;
    }
}
