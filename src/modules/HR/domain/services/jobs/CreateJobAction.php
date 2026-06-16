<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\domain\entities\Job;
use PostApi\shared\app\http\requests\Request;

class CreateJobAction
{
    public static function execute()
    {
        $request = new Request();
        $params = $request->body;

        $departmentRepository = new DepartmentRepository();
        $jobRepository = new JobRepository();

        $job = new Job();
        $job->setTitle($params['title']);
        $department = $departmentRepository->findOne($params['department_id']);
        $job->setDepartment($department);

        $jobRepository->create($job);
        return $job;
    }
}
