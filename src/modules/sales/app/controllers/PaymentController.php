<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Exception;
use Override;
use PostApi\modules\sales\domain\services\Payment\CreatePaymentAction;
use PostApi\modules\sales\domain\services\Payment\DeletePaymentAction;
use PostApi\modules\sales\domain\services\Payment\GetPaymentCollectionAction;
use PostApi\modules\sales\domain\services\Payment\GetPaymentItemAction;
use PostApi\modules\sales\domain\services\Payment\UpdatePaymentAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class PaymentController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        $serin = GetPaymentCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        $serin = GetPaymentItemAction::execute($id);
        return Json::toJson($serin);
    }

    #[Override]
    public function create(Request $request)
    {        
        $body = $request->body;       
        if (!isset($body['amount'], $body['status'], $body['currency'], $body['order_id'])) {
            return ViewError::viewProplem("creating payment error", "missing required paramters error", 1, "missing required paramters amount , status ,currency , order_id ", 400);
        }
        try {
            $checkout = CreatePaymentAction::execute($body);
            $url = stripcslashes($checkout->url);           
            http_response_code(201);
            header("Location: " . $url , true , 303);
            exit();
        } catch (Exception $error) {
            return ViewError::viewProplem(type: "create payment error ", title: "internal server error", status: true, detail: "unexpected error creating payment", statusCode: 500);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {       
        $body = $request->body;
        if (!isset($body['amount'], $body['status'], $body['currency'], $body['order_id'])) {
            return ViewError::viewProplem("updating payment error", "missing required paramters error", 1, "missing required paramters amount , status ,currency , order_id ", 400);
        }
        try {
            UpdatePaymentAction::execute($id, $body);
            http_response_code(200);
            return Json::toJson(["message" => "Upadte Payment Succesfully"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update payment error ", title: "incorrect paramter", status: true, detail: "no payment for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeletePaymentAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "Delete Payment Succesfully"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete payment error ", title: "incorrect paramter", status: true, detail: "no payment for this id", statusCode: 400);
        }
    }
}
