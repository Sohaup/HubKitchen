<?php

namespace PostApi\modules\manegers\domain\services\task;

use PostApi\modules\manegers\app\DB\repositories\TaskRepository;
use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\modules\manegers\domain\entities\Task;

class CreateTaskAction
{
    public static function execute(array $params): Task
    {        
        $name = $params['name'] ?? '';
        $description = $params['description'] ?? '';
        $manegerId = $params['maneger_id'] ?? null;
        $departmentId = $params['department_id'] ?? null;
        $manegerRepo = new ManegerRepository();
        $maneger = $manegerRepo->findOne($manegerId);
        $deptRepo = new DepartmentRepository();
        $department = $deptRepo->findOne((int)$departmentId);
        $task = new Task();
        $task->setName($name);
        $task->setDescription($description);
        $task->setManeger($maneger);
        $task->setDepartment($department);
        $repo = new TaskRepository();
        $repo->create($task);
        return $task;
    }
}
