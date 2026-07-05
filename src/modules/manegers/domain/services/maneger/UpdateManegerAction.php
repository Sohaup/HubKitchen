<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateManegerAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new ManegerRepository();
        $maneger = $repo->findOne($id);
        if (!$maneger) {
            throw new \Exception("maneger not found");
        }
        if (isset($params['rank'])) {
            $maneger->setRank((int)$params['rank']);
        }
        if (isset($params['user_id'])) {
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($params['user_id']);
            $maneger->setUser($user);
        }
        if (isset($params['department_id'])) {
            $deptRepo = new DepartmentRepository();
            $dept = $deptRepo->findOne((int)$params['department_id']);
            $maneger->setDepartment($dept);
        }
        $repo->update($maneger);
        return $maneger;
    }
}
