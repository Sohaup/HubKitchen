<?php

namespace PostApi\modules\HR\domain\services\shifts;

use PostApi\modules\HR\app\DB\repositories\ShiftRepository;

class DeleteShiftAction
{
    public static function execute(string $id)
    {
        $shiftRepository = new ShiftRepository();
        $shift =  $shiftRepository->findOne($id);
        $shiftRepository->delete($id);
    }
}
