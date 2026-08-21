<?php
namespace PostApi\modules\sales\domain\services\Payment;

use PostApi\modules\sales\app\DB\repositories\PaymentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPaymentCollectionAction {
    public static function execute(array $items = null) {
        $paymentRepo = new PaymentRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $paymentRepo->findAll());
        return $serin;
    }
}