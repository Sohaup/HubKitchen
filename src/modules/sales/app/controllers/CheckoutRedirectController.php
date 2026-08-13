<?php

namespace PostApi\modules\sales\app\controllers;

use PostApi\shared\app\http\responses\success\json\Json;
// require_once __DIR__ . "/../../helpers/templates/SuccessCheckout.html";

class CheckoutRedirectController
{
    public function success()
    {
        $view = file_get_contents(__DIR__ . "/../../helpers/templates/SuccessCheckout.html");
        return $view;
    }

    public function failed()
    {
        http_response_code(500);
        $view = file_get_contents(__DIR__ . "/../../helpers/templates/FailedCheckout.html");
        return $view;
    }
}
