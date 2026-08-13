<?php

namespace PostApi\modules\sales\domain\valueObjects\types\payment;

enum PaymentStatusType: string
{
    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
    case REFUNDED = "refunded";
}
