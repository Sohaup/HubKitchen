<?php

namespace PostApi\modules\HR\domain\services\applications;

use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationItemAction
{
    public static function execute(int $id)
    {
        $applicationRepository = new ApplicationRepository();
        $application = $applicationRepository->findOne($id);
        $serin = SerializeToSerin::serialize($application);
        return $serin;
    }
}
