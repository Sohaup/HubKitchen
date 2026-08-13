<?php

namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\PaymentRepository;

class DeletePaymentAction
{
    public static function execute(int $id)
    {
        $paymentRepo = new PaymentRepository();
        $payment = $paymentRepo->find($id);
        if ($payment) {
            $paymentRepo->delete($id);
        }
    }
}
