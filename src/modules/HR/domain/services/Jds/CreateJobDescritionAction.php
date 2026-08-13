<?php

namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\modules\HR\app\DB\repositories\ShiftRepository;
use PostApi\modules\HR\domain\entities\JobDescription;

class CreateJobDescritionAction
{
    public static function execute(array $params)
    {        
        $shiftRepository = new ShiftRepository();
        $jdRepository = new JobDescriptionRepository();
        $jd = new JobDescription();
        $jd->setName($params['name']);
        $jd_shift = $shiftRepository->findOne($params['shift_id']);
        $jd->setShift($jd_shift);
        $jdRepository->create($jd);
        return $jd;
    }
}
