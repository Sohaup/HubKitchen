<?php

namespace PostApi\modules\HR\domain\services\employee;

use Override;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\modules\HR\domain\entities\Employee;
use PostApi\modules\HR\domain\EntityListeners\CreateEmployeeListener;
use PostApi\shared\app\http\requests\Request;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateEmployeeAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $createEmployeeEvent = "";
    private Employee $employee;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new CreateEmployeeListener());
    }

    public function execute()
    {
        $request = new Request();
        $params = $request->body;
        $repo = new EmployeeRepository();
        $userRepo = new UserRepository();
        $departmentRepo = new DepartmentRepository();
        $addreseRepo = new AddreseRepository();
        $jobDescriptionRepo = new JobDescriptionRepository();
        $entity = new Employee();
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
        $repo->create($entity);
        $this->createEmployeeEvent = "created";
        $this->employee = $entity;
        $this->notify();
        return $entity;
    }
    #[Override]
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }
    #[Override]
    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }
    #[Override]
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getEvent()
    {
        return $this->createEmployeeEvent;
    }

    public function getEmployee()
    {
        return $this->employee;
    }
}
