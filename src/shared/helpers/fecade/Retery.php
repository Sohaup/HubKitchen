<?php

namespace PostApi\shared\helpers\fecade;

require_once __DIR__ . "/../utilities/executionWithRetery.php";

class Retery
{
    public static function execute(callable $task)
    {
        return executeWithRetry($task, 3, 2);
    }
}
