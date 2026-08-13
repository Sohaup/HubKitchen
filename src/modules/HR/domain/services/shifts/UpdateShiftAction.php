<?php

namespace PostApi\modules\HR\domain\services\shifts;

use PostApi\modules\HR\app\DB\repositories\ShiftRepository;

class UpdateShiftAction
{
    public static function execute(string $id , array $params)
    {       
        $shiftRepository = new ShiftRepository();
        $shift = $shiftRepository->findOne($id);
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
