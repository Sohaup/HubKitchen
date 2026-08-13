<?php

namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\modules\HR\app\DB\repositories\ShiftRepository;

class UpdateJobDescriptionAction
{
    public static function execute(int $id , array $params)
    {        
        $shiftRepository = new ShiftRepository();
        $jdRepository = new JobDescriptionRepository();
        $jd = $jdRepository->findOne($id);
        $jd->setName($params['name']);
        $jd_shift = $shiftRepository->findOne($params['shift_id']);
        $jd->setShift($jd_shift);
        $jdRepository->update($jd);
    }
}
