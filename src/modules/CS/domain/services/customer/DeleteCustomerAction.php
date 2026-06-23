<?php

namespace PostApi\modules\CS\domain\services\customer;

use PostApi\modules\CS\app\DB\repositories\CustomerRepository;

class DeleteCustomerAction
{
    public static function execute(string $id)
    {
        $repo = new CustomerRepository();
        $customer = $repo->findOne($id);
        if ($customer) {
            $repo->delete($id);
        }
    }
}
