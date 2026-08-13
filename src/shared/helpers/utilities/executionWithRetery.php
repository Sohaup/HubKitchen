<?php

function executeWithRetry(callable $task, int $maxAttempts = 3, int $delaySeconds = 2)
{
    $lastException = null;

    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        try {
            return $task();
        } catch (Exception $e) {
            $lastException = $e;
            if ($attempt === $maxAttempts) {
                break;
            }
            error_log(" attempt number : {$attempt} from {$maxAttempts}. retry after {$delaySeconds} seconds...");
            sleep($delaySeconds);
        }
    }
    throw new Exception("all attempts is failed ({$maxAttempts} attempts). reason : " . $lastException->getMessage());
}
