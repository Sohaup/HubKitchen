<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;


class UpdateManegerAction
{
    public static function execute(string $id, array $params)
    {
        $repo = new ManegerRepository();
        $maneger = $repo->findOne($id);
        if (!$maneger) {
            throw new \Exception("maneger not found");
        }
        if (isset($params['rank'])) {
            $maneger->setRank((int)$params['rank']);
        }
        if (isset($params['user_id'])) {
            $maneger->setUserID($params['user_id']);
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
