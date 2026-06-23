<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\CS\domain\entities\Customer;
use PostApi\shared\app\http\requests\Request;

class CreateCustomerAction
{
    public static function execute(): Customer
    {
        $request = new Request();
        $params = $request->body;
        $userId = $params['user_id'] ?? null;
        $country = $params['country'] ?? '';
        $userRepo = new UserRepository();
        $user = $userRepo->findOne($userId);
        $customer = new Customer();
        $customer->setUser($user);
        $customer->setCountry($country);
        $repo = new CustomerRepository();
        $repo->create($customer);        
        return $customer;
    }
}
