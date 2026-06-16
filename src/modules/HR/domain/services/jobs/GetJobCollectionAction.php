<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobCollectionAction
{
    public static function execute()
    {
        $jobRepository = new JobRepository();
        $jobs = $jobRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($jobs);
        return $serin;
    }
}
