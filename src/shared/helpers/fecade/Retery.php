<?php

namespace PostApi\shared\helpers\fecade;

use Error;
use Exception;

require_once __DIR__ . "/../utilities/executionWithRetery.php";

class Retery
{
    public static function execute(callable $task)
    {
        try {
            return executeWithRetry($task, 3, 2);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
