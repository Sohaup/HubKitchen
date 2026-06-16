<?php

namespace PostApi\modules\HR\domain\services\shifts;


use PostApi\modules\HR\app\DB\repositories\ShiftRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetShiftCollectionAction
{
    public static function execute()
    {
        $shiftRepository = new ShiftRepository();
        $shifts = $shiftRepository->findAll();
        $serinJson = SerializeToSerin::serializeCollection($shifts);       
        return $serinJson;
    }
}
