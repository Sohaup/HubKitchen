<?php

namespace PostApi\modules\HR\domain\services\employee;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;


class UpdateEmployeeAction
{
    public static function execute(string $id, array $params)
    {
        $repo = new EmployeeRepository();
        $departmentRepo = new DepartmentRepository();
        $addreseRepo = new AddreseRepository();
        $jobDescriptionRepo = new JobDescriptionRepository();
        $entity = $repo->findOne($id);
        $entity->setEmployeeStatus($params['employeeStatus']);
        $entity->setMartialStatus($params['martialStatus']);
        $entity->setUserId($params['user_id']);
        $job = $jobDescriptionRepo->findOne($params['job_id']);
        $entity->setJob($job);
        $entity->setManagerId($params['manager_id']);
        $department = $departmentRepo->findOne($params['department_id']);
        $entity->setDepartment($department);
        $addresse = $addreseRepo->findOne($params['addresse_id']);
        $entity->setAddress($addresse);
        $repo->update($entity);
    }
}
