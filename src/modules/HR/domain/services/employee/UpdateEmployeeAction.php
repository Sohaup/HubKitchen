<?php

namespace PostApi\modules\HR\domain\services\employee;

use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateEmployeeAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new EmployeeRepository();
        $userRepo = new UserRepository();
        $departmentRepo = new DepartmentRepository();
        $addreseRepo = new AddreseRepository();
        $jobDescriptionRepo = new JobDescriptionRepository();
        $entity = $repo->findOne($id);
        $entity->setEmployeeStatus($params['employeeStatus']);
        $entity->setMartialStatus($params['martialStatus']);
        $user = $userRepo->findOne($params['user_id']);
        $entity->setUser($user);
        $job = $jobDescriptionRepo->findOne($params['job_id']);
        $entity->setJob($job);
        $manager = $userRepo->findOne($params['manager_id']);
        $entity->setManager($manager);
        $department = $departmentRepo->findOne($params['department_id']);
        $entity->setDepartment($department);
        $addresse = $addreseRepo->findOne($params['addresse_id']);
        $entity->setAddress($addresse);
        $repo->update($entity);
    }
}
