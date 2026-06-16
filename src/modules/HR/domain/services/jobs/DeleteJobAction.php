<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;

class DeleteJobAction
{
    public static function execute(int $id)
    {
        $jobRepository = new JobRepository();
        $job = $jobRepository->findOne($id);
        if ($job) {
            $jobRepository->delete($id);
        }
    }
}
