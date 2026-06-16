<?php

namespace PostApi\modules\HR\domain\services\shifts;

use PostApi\modules\HR\domain\entities\Shift;
use PostApi\shared\helpers\fecade\SerializeToSerin;


class GetShiftItemAction
{
    public static function execute(Shift $shift)
    {
        $serin = SerializeToSerin::serialize($shift);
        return $serin;
    }
}
