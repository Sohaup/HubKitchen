<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCustomerCollectionAction
{
    public static function execute(array $items = null)
    {
        $customerRepository = new CustomerRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $customerRepository->findAll());
        return $serin;
    }
}
