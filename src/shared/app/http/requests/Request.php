<?php 
namespace PostApi\shared\app\http\requests;

use PostApi\shared\helpers\fecade\Urls;

class Request {
    public string $path = "";
    public string $method = "";
    public function __construct()
    {
       $this->path = Urls::transformUrl($_SERVER['REQUEST_URI']);    
       $this->method = $_SERVER['REQUEST_METHOD'];   
    }
}