<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobItemAction
{
    public static function execute(int $id)
    {
        $jobRepository = new JobRepository();
        $job = $jobRepository->findOne($id);
        $serin = SerializeToSerin::serialize($job);
        return $serin;
    }
}
