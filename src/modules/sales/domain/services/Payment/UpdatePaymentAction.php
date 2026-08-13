<?php

namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\app\DB\repositories\PaymentRepository;
use PostApi\modules\sales\domain\entities\Payment;

class UpdatePaymentAction
{
    public static function execute(int $id , array $body)
    {
        $paymentRepo = new PaymentRepository();
        $orderRepo = new OrderRepository();       
        $payment = $paymentRepo->find($id);
        if (isset($body['amount'])) {
            $payment->setAmount($body['amount']);
        }
        if (isset($body['currency'])) {
            $payment->setAmount($body['currency']);
        }
        if (isset($body['status'])) {
            $payment->setAmount($body['status']);
        }
        if (isset($body['order_id'])) {
            $order = $orderRepo->findOne($body['order_id']);
            $payment->setOrder($order);
        }
        $paymentRepo->update($payment);
    }
}
