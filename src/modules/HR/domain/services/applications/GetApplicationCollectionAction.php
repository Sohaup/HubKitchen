<?php

namespace PostApi\modules\HR\domain\services\applications;

use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationCollectionAction
{
    public static function execute(array $items = null)
    {
        $applicationRepository = new ApplicationRepository();
        $applications = $items ?? $applicationRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($applications);
        return $serin;
    }
}
