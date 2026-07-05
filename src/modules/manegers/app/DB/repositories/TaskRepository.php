<?php
namespace PostApi\modules\manegers\app\DB\repositories;

use PostApi\modules\manegers\app\DB\models\TaskMapper;
use PostApi\modules\manegers\domain\entities\Task;
use PostApi\shared\templates\DB_Trait;

class TaskRepository
{
    use DB_Trait;
    private TaskMapper $taskMapper;
    public function __construct()
    {
        $this->initialize();
        $this->taskMapper = new TaskMapper($this->postgre->pdo);
    }

    public function findOne(int $id) {
        return $this->taskMapper->findOne($id);
    }

    public function findAll() {
        return $this->taskMapper->findAll();
    }

    public function create(Task $task) {
        $this->taskMapper->insert($task);
    }

    public function update(Task $task) {
        $this->taskMapper->update($task);
    }

    public function delete(int $id) {
        $this->taskMapper->delete($id);
    }
}
