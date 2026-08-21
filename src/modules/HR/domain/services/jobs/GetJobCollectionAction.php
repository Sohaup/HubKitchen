<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobCollectionAction
{
    public static function execute(array $items = null)
    {
        $jobRepository = new JobRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $jobRepository->findAll());
        return $serin;
    }
}
