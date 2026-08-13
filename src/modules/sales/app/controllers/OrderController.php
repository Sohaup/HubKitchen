<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\app\DB\repositories\CustomerRepository;
use PostApi\modules\sales\app\DB\repositories\OrderRepository;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\modules\sales\domain\services\order\CreateOrderAction;
use PostApi\modules\sales\domain\services\order\DeleteOrderAction;
use PostApi\modules\sales\domain\services\order\GetOrderCollectionAction;
use PostApi\modules\sales\domain\services\order\GetOrderItemAction;
use PostApi\modules\sales\domain\services\order\UpdateOrderAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class OrderController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        $serin = GetOrderCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetOrderItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display order error ", title: "incorrect paramter", status: true, detail: "there is no corresponding order for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {        
        $params = $request->body;
        if (!isset($params['customer_id'], $params['cart_id'])) {
            return ViewError::viewProplem("creating order error", "missing required paramters error", 1, "missing required paramters customer_id, cart_id", 400);
        }
        try {
            $customerRepo = new CustomerRepository();
            $customer = $customerRepo->findOne($params['customer_id']);
            if (!$customer) {
                return ViewError::viewProplem("creating order error", "invalid customer_id", 1, "customer not found", 400);
            }
            $cartRepo = new CartRepository();
            $cart = $cartRepo->findOne($params['cart_id']);
            if (!$cart) {
                return ViewError::viewProplem("creating order error", "invalid cart_id", 1, "cart not found", 400);
            }
            $order = new Order();
            $order->setCustomer($customer);
            $order->setCart($cart);
            $createOrderAction = new CreateOrderAction();
            $serin = $createOrderAction->execute($order);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create order error ", title: "incorrect paramter", status: true, detail: "unexpected error creating order", statusCode: 400);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {        
        $params = $request->body;
        if (!isset($params['customer_id'], $params['cart_id'])) {
            return ViewError::viewProplem("updating order error", "missing required paramters error", 1, "missing required paramters customer_id, cart_id", 400);
        }
        try {
            $orderRepository = new OrderRepository();
            $order = $orderRepository->findOne($id);
            if (!$order) {
                return ViewError::viewProplem(type: "update order error ", title: "incorrect paramter", status: true, detail: "order not found", statusCode: 400);
            }
            $customerRepo = new CustomerRepository();
            $customer = $customerRepo->findOne($params['customer_id']);
            if (!$customer) {
                return ViewError::viewProplem("updating order error", "invalid customer_id", 1, "customer not found", 400);
            }
            $cartRepo = new CartRepository();
            $cart = $cartRepo->findOne($params['cart_id']);
            if (!$cart) {
                return ViewError::viewProplem("updating order error", "invalid cart_id", 1, "cart not found", 400);
            }
            $order->setCustomer($customer);
            $order->setCart($cart);

            UpdateOrderAction::execute($order);
            http_response_code(200);
            return Json::toJson(["message" => "update order successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update order error ", title: "incorrect paramter", status: true, detail: "there is no corresponding order for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            $deleteOrderAction = new DeleteOrderAction();
            $deleteOrderAction->execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "delete order successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete order error ", title: "incorrect paramter", status: true, detail: "there is no corresponding order for this id", statusCode: 400);
        }
    }
}
