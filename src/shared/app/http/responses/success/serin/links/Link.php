<?php
namespace PostApi\shared\app\http\responses\success\serin\links;

class Link {
    public function __construct(public array $rel , public string $href)
    {
       
    }
} 