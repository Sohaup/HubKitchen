<?php

namespace PostApi\modules\HR\domain\services\jobs;

use PostApi\modules\HR\app\DB\repositories\JobRepository;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateJobAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $jobRepository = new JobRepository();
        $departmentRepository = new DepartmentRepository();
        $job = $jobRepository->findOne($id);
        if ($job) {
            if (isset($params['title'])) {
                $job->setTitle($params['title']);
            }
            if (isset($params['department_id'])) {
                $department = $departmentRepository->findOne($params['department_id']);
                $job->setDepartment($department);
            }
            $jobRepository->update($job);
        }
    }
}
