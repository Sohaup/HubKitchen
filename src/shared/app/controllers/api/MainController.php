<?php

namespace PostApi\shared\app\controllers\api;

class MainController
{
    public function get()
    {
        return file_get_contents(require_once __DIR__ . "/../../../../public/api-docs.html");
    }
}
