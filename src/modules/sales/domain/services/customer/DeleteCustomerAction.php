<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\helpers\adapters\stripe\StripeCustomer;

class DeleteCustomerAction
{
    public static function execute(string $id)
    {
        $customerRepository = new CustomerRepository();
        $stripe = new StripeCustomer();
        $customer = $customerRepository->findOne($id);
        if ($customer) {
            $customerRepository->delete($id);
            $stripe->delete($customer->getStripeId());
        }
    }
}
