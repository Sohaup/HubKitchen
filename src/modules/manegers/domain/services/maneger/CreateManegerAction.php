<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use Override;
use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\modules\manegers\domain\entities\Maneger;
use PostApi\modules\manegers\domain\entityListeners\CreateManegerListener;
use PostApi\shared\app\http\requests\Request;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateManegerAction implements SplSubject
{
    private SplObjectStorage $observers;
    private Maneger $maneger;
    private string $createManegerEvent = "";
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new CreateManegerListener());
    }
    
    public function execute(): Maneger
    {
        $request = new Request();
        $params = $request->body;
        $userId = $params['user_id'] ?? null;
        $rank = (int)($params['rank'] ?? 0);
        $departmentId = $params['department_id'] ?? null;
        $userRepo = new UserRepository();
        $user = $userRepo->findOne($userId);
        $deptRepo = new DepartmentRepository();
        $department = $deptRepo->findOne((int)$departmentId);
        $maneger = new Maneger();
        $maneger->setUser($user);
        $maneger->setRank($rank);
        $maneger->setDepartment($department);
        $repo = new ManegerRepository();
        $repo->create($maneger);
        $this->maneger = $maneger;
        $this->createManegerEvent = "created";
        $this->notify();
        return $maneger;
    }

    public function attach(\SplObserver $observer): void
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
        return $this->createManegerEvent;
    }
    public function getManeger()
    {
        return $this->maneger;
    }
}
