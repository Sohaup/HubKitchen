<?php

namespace PostApi\modules\manegers\domain\services\task;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\modules\manegers\app\DB\repositories\TaskRepository;

class UpdateTaskAction
{
    public static function execute(int $id , array $params)
    {       
        $repo = new TaskRepository();
        $task = $repo->findOne($id);
        if (!$task) {
            throw new \Exception("task not found");
        }
        if (isset($params['name'])) {
            $task->setName($params['name']);
        }
        if (isset($params['description'])) {
            $task->setDescription($params['description']);
        }
        if (isset($params['maneger_id'])) {
            $manegerRepo = new ManegerRepository();
            $maneger = $manegerRepo->findOne($params['maneger_id']);
            $task->setManeger($maneger);
        }
        if (isset($params['department_id'])) {
            $deptRepo = new DepartmentRepository();
            $department = $deptRepo->findOne((int)$params['department_id']);
            $task->setDepartment($department);
        }
        $repo->update($task);
        return $task;
    }
}
