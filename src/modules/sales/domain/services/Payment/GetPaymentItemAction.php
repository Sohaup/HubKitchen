<?php
namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\PaymentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPaymentItemAction {
    public static function execute(int $id) {
        $paymentRepo = new PaymentRepository();
        $payment = $paymentRepo->find($id);
        $serin = SerializeToSerin::serialize($payment);
        return $serin;
    } 
}