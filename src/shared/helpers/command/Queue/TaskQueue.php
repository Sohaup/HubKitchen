<?php

namespace PostApi\shared\helpers\command\Queue;

use PostApi\shared\helpers\command\ClousreCommand;

class TaskQueue
{
    /** @var array<ClousreCommand> */
    private array $tasks = [];
    private array $tasksReslut = [];

    public function push(ClousreCommand $task)
    {
        $this->tasks[] = $task;
    }

    public function pop(): ?ClousreCommand
    {
        if (!$this->isEmpty()) {
            return  array_shift($this->tasks);
        }
        return null;
    }

    public function isEmpty(): bool
    {
        return empty($this->tasks);
    }

    public function execute()
    {
        $this->tasksReslut = [];
        while (!$this->isEmpty()) {
            $task = $this->pop();
            if ($task instanceof ClousreCommand) {
                $this->tasksReslut[] = $task->execute();
            }
        }

        return $this->tasksReslut;
    }
}
