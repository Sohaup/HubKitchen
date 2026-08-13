<?php

namespace PostApi\shared\app\http\middlewares;

use Closure;
use PostApi\shared\app\http\requests\Request;

class ThrottleMiddleware implements Middleware
{
    private int $maxRequests;
    private int $timeWindow;
    private string $cacheDir;

    public function __construct(int $maxRequests = 5, int $timeWindow = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindow;   
        $projectSrc = dirname(__DIR__, 5); 
        $this->cacheDir = $projectSrc . "/public/uploads/rate_limits";        
       
        if (!is_dir($this->cacheDir)) {         
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function handle(Request $request, Closure $next)
    {
        $ip = $this->getClientIP();
        $safeIp = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $ip);
        $cacheFile = $this->cacheDir . "/limit_" . $safeIp . ".json";        
        $currentTime = time();
        if (!file_exists($cacheFile)) {
            $this->saveCache($cacheFile, $currentTime, 1);
            return $next($request);
        }
        $data = json_decode(file_get_contents($cacheFile), true);
        $elapsedTime = $currentTime - $data['start_time'];
        if ($elapsedTime < $this->timeWindow) {
            if ($data['count'] >= $this->maxRequests) {
                http_response_code(429);
                header('Content-Type: application/json');
                echo json_encode([
                    "status" => "error",
                    "message" => "Too many requests. Rate limit exceeded.",
                    "retry_after" => $this->timeWindow - $elapsedTime
                ]);
                exit();
            }

            $this->saveCache($cacheFile, $data['start_time'], $data['count'] + 1);
        } else {
            $this->saveCache($cacheFile, $currentTime, 1);
        }

        return $next($request);
    }

    private function getClientIP(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    private function saveCache(string $file, int $startTime, int $count): void
    {
        $data = [
            'start_time' => $startTime,
            'count' => $count
        ];
        file_put_contents($file, json_encode($data));      
        chmod($file, 0666); 
    }
}
