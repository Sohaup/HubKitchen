<?php

namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\app\DB\repositories\PaymentRepository;
use PostApi\modules\sales\domain\entities\Payment;
use PostApi\modules\sales\helpers\adapters\stripe\StripeCheckout;
use PostApi\shared\helpers\fecade\Redirect;

class CreatePaymentAction
{
    public static function execute(array $body)
    {       
        $paymentRepo = new PaymentRepository();
        $orderRepo = new OrderRepository();
        $payment = new Payment();
        $payment->setAmount($body['amount']);
        $payment->setCurrency($body['currency']);
        $payment->setStatus($body['status']);
        $order = $orderRepo->findOne($body['order_id']);
        $payment->setOrder($order);
        $payment->setStripePaymentIntentId("current");
        $stripe = new StripeCheckout();
        $checkout = $stripe->create($payment);
        $payment->setStripeSessionId($checkout['id']);
        $paymentRepo->create($payment);
        $serin = GetPaymentItemAction::execute($payment->getId());
        
        return $checkout;
    }
}
