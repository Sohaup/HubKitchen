<?php
namespace PostApi\shared\helpers\command;

use Closure;
use Override;

class ClousreCommand implements Command {
    private Closure $task;
    public function __construct(callable $task)
    {
       $this->task = $task;
    } 
    #[Override]
    public function execute()
    {
        return ($this->task)();
    }
} 