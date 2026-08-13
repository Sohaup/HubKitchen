<?php

namespace PostApi\modules\sales\domain\services\customer;

use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\helpers\adapters\stripe\StripeCustomer;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateCustomerAction
{
    public static function execute(Customer $customer)
    {
        $customerRepository = new CustomerRepository();
        $stripe = new StripeCustomer();
        $stripeCustomer = $stripe->create($customer);
        $customer->setStripeId($stripeCustomer->id);
        $customerRepository->create($customer);       
        $serin = SerializeToSerin::serialize($customer);
        return $serin;
    }
}
