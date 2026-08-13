<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\helpers\adapters\stripe\StripeCustomer;

class UpdateCustomerAction
{
    public static function execute(Customer $customer)
    {
        $customerRepository = new CustomerRepository();
        $stripe = new StripeCustomer();
        $customerRepository->update($customer);
        $stripe->update($customer);
    }
}
