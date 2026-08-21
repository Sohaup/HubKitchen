<?php

namespace PostApi\modules\HR\domain\services\payroll;

use PostApi\modules\HR\app\DB\repositories\PayrollRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPayrollCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new PayrollRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
     
    }
}
