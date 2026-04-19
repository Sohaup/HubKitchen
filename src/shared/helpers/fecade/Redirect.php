<?php

namespace PostApi\shared\helpers\fecade;

class Redirect
{
    public static function ToRoute(string $routePath)
    {
        header("Location: $routePath", true, 307);
        die();
    }
}
