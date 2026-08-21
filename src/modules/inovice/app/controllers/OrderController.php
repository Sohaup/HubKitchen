<?php

namespace PostApi\modules\inovice\app\controllers;
use Error;
use Exception;
use PostApi\modules\inovice\domain\services\order\CreateOrderAction;
use PostApi\modules\inovice\domain\services\order\DeleteOrderAction;
use PostApi\modules\inovice\domain\services\order\GetOrderCollectionAction;
use PostApi\modules\inovice\domain\services\order\GetOrderItemAction;
use PostApi\modules\inovice\domain\services\order\UpdateOrderAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class OrderController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $orderRepository = new \PostApi\modules\inovice\app\DB\repositories\OrderRepository();
            $critiria = [];

            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['prucher_id'])) {
                $critiria['prucher_id'] = $body['prucher_id'];
            }
            if (isset($body['created_at'])) {
                $critiria['created_at'] = $body['created_at'];
            }

            if (!empty($critiria)) {
                $orders = $orderRepository->findBy($critiria);
            } else {
                $orders = $orderRepository->findAll();
            }

            $serin = GetOrderCollectionAction::execute($orders);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['prucher_id'])) {
            return ViewError::viewProplem('create order error', 'missing required paramters', 1, 'missing required paramters prucher_id', 400);
        }
        try {
            $order = CreateOrderAction::execute($body);
            $serin = GetOrderItemAction::execute($order->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetOrderItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, "no order for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {      
        $body = $request->body;
        if (!isset($body['prucher_id'])) {
            return ViewError::viewProplem('update order error', 'missing required paramters', 1, 'missing required paramters prucher_id', 400);
        }
        try {
            UpdateOrderAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'order updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no order for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteOrderAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'order deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no order for this id", 400);
        }
    }
}
