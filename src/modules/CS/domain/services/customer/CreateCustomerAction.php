<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\modules\CS\domain\entities\Customer;

class CreateCustomerAction
{
    public static function execute(array $params): Customer
    {       
        $userId = $params['user_id'] ?? null;
        $country = $params['country'] ?? '';
        $customer = new Customer();
        $customer->setUserId($userId);
        $customer->setCountry($country);
        $repo = new CustomerRepository();
        $repo->create($customer);        
        return $customer;
    }
}
