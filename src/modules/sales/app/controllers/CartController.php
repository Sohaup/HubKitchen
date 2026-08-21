<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\modules\sales\domain\services\cart\CreateCartAction;
use PostApi\modules\sales\domain\services\cart\DeleteCartAction;
use PostApi\modules\sales\domain\services\cart\GetCartCollectionAction;
use PostApi\modules\sales\domain\services\cart\GetCartItemAction;
use PostApi\modules\sales\domain\services\cart\UpdateCartAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CartController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $cartRepository = new CartRepository();
            $critiria = [];

            if (isset($body['user_id'])) {
                $critiria['user_id'] = $body['user_id'];
            }

            if (isset($body['price'])) {
                $critiria['price'] = $body['price'];
            } elseif (isset($body['greater_than_price'])) {
                $critiria['greater_than_price'] = $body['greater_than_price'];
            } elseif (isset($body['less_than_price'])) {;
                $critiria['less_than_price'] = $body['less_than_price'];
            } elseif (isset($body['greater_than_or_equal_price'])) {
                $critiria['greater_than_or_equal_price'] = $body['greater_than_or_equal_price'];
            } elseif (isset($body['less_than_or_equal_price'])) {
                $critiria['less_than_or_equal_price'] = $body['less_than_or_equal_price'];
            }

            if (!empty($critiria)) {
                $carts = $cartRepository->findBy($critiria);
            } else {
                $carts = $cartRepository->findAll();
            }
            $serin = GetCartCollectionAction::execute($carts);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display cart error ", title: "incorrect paramter", status: true, detail: "internal server error", statusCode: 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetCartItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display cart error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {
        $params = $request->body;
        if (!isset($params['user_id'], $params['price'])) {
            return ViewError::viewProplem("creating cart error", "missing required paramters error", 1, "missing required paramters  user_id, price", 400);
        }
        try {
            $cart = new Cart();
            $cart->setUserId($params['user_id']);
            $cart->setPrice((float)$params['price']);
            $serin = CreateCartAction::execute($cart);
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create cart error ", title: "incorrect paramter", status: true, detail: "unexpected error creating cart", statusCode: 500);
        }
    }

    #[Override]
    public function update(Request $request, string $id)
    {
        $params = $request->body;
        if (!isset($params['user_id'], $params['price'])) {
            return ViewError::viewProplem("updating cart error", "missing required paramters error", 1, "missing required paramters user_id, price", 400);
        }
        try {
            $cartRepository = new CartRepository();
            $cart = $cartRepository->findOne($id);
            if (!$cart) {
                return ViewError::viewProplem(type: "update cart error ", title: "incorrect paramter", status: true, detail: "cart not found", statusCode: 400);
            }
            $cart->setUser($params['user_id']);
            $cart->setPrice((float)$params['price']);
            UpdateCartAction::execute($cart);
            http_response_code(200);
            return Json::toJson(["message" => "update cart successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update cart error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            DeleteCartAction::execute($id);
            http_response_code(200);
            return Json::toJson(["message" => "delete cart successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete cart error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart for this id", statusCode: 400);
        }
    }
}
