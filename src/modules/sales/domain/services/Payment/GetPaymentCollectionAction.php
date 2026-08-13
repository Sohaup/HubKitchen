<?php
namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\PaymentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPaymentCollectionAction {
    public static function execute() {
        $paymentRepo = new PaymentRepository();
        $payments = $paymentRepo->findAll();
        $serin = SerializeToSerin::serializeCollection($payments);
        return $serin;
    }
}