<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;

class UpdateCustomerAction
{
    public static function execute(string $id, array $params)
    {
        $repo = new CustomerRepository();
        $customer = $repo->findOne($id);
        if (!$customer) {
            throw new \Exception("customer not found");
        }
        if (isset($params['country'])) {
            $customer->setCountry($params['country']);
        }
        if (isset($params['user_id'])) {
            $customer->setUserId($params['user_id']);
        }
        $repo->update($customer);
        return $customer;
    }
}
