<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;

class AssignApplicationToJobAction
{
    public static function execute(int $applicationId, int $jobId)
    {
        $applicationRepository = new ApplicationRepository();
        $jobRepository = new JobRepository();
        $application = $applicationRepository->findOne($applicationId);
        $job = $jobRepository->findOne($jobId);
        if ($application && $job) {
            return $jobRepository->assignApplicationToJob($application, $job);
        }
    }
}
