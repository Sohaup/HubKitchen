<?php

namespace PostApi\modules\HR\domain\services\shifts;


use PostApi\modules\HR\app\DB\repositories\ShiftRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetShiftCollectionAction
{
    public static function execute(array $items = null)
    {
        $shiftRepository = new ShiftRepository();
        $serinJson = SerializeToSerin::serializeCollection($items ?? $shiftRepository->findAll());       
        return $serinJson;
    }
}
