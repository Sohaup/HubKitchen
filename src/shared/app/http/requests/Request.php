<?php

namespace PostApi\shared\app\http\requests;

use PostApi\shared\helpers\fecade\Urls;

class Request
{
    public string $path = "";
    public string $method = "";
    public array $params = [];
    public array $body = [];
    public array $headers = [];
    private ?string $token = null;
    public function __construct()
    {
        $this->path = Urls::transformUrl($_SERVER['REQUEST_URI']);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $params = array_merge($_GET, $_POST);
        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }
        $jsonParamters = file_get_contents("php://input");
        if ($jsonParamters) {
            $this->body = json_decode($jsonParamters, true);
        }
        $this->headers = $this->getAllHeaders();
    }

    public function getAllHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {

            if (substr($name, 0, 5) == 'HTTP_') {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            } elseif (in_array($name, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'])) {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $name))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    public function getToken()
    {
        if (isset($this->headers['Authorization'])) {
            $authHeader = $this->headers['Authorization'];
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $tokenValue =  $matches[1] ?? false;
                return $tokenValue;
            }
        }
        return false;
    }
}
