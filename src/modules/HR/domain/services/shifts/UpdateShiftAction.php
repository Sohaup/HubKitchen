<?php

namespace PostApi\modules\HR\domain\services\shifts;

use DateTime;
use PostApi\modules\HR\app\DB\repositories\ShiftRepository;
use PostApi\modules\HR\domain\entities\Shift;
use PostApi\shared\app\http\requests\Request;

class UpdateShiftAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $shiftRepository = new ShiftRepository();
        $shift = $shiftRepository->findOne($id);
        // print_r($shift);
        // echo "\n";
        // echo "\n";
        $shift->setShiftName($params['shift_name']);
        $shift->setStartTime($params['start_time']);
        $shift->setShiftName($params['shift_name']);
        $shift->setEndTime($params['end_time']);
        $shift->setBreakDuration($params['break_duration_minutes']);
        $shift->setIsOverNight($params['is_overnight']);
        $shift->setIsActive($params['is_active']);
        $shiftRepository->update($shift);
        return $shift;
    }
}
