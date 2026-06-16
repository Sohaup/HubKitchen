<?php 
namespace PostApi\shared\app\http\responses\errors\problem;

class ProblemJson {
    public function __construct(public string $type , public string $title , public int $status , public string $detail )
    {
        
    }
}