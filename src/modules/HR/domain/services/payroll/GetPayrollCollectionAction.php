<?php

namespace PostApi\modules\HR\domain\services\payroll;

use PostApi\modules\HR\app\DB\repositories\PayrollRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPayrollCollectionAction
{
    public static function execute()
    {
        $repo = new PayrollRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
