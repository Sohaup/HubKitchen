<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateCustomerAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new CustomerRepository();
        $customer = $repo->findOne($id);
        if (!$customer) {
            throw new \Exception("customer not found");
        }
        if (isset($params['country'])) {
            $customer->setCountry($params['country']);
        }
        if (isset($params['user_id'])) {
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($params['user_id']);
            $customer->setUser($user);
        }
        $repo->update($customer);
        return $customer;
    }
}
