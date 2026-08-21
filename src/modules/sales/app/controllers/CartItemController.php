<?php

namespace PostApi\modules\sales\app\controllers;

use Error;
use Override;
use PostApi\modules\sales\app\DB\repositories\CartItemRepository;
use PostApi\modules\sales\app\DB\repositories\CartRepository;
use PostApi\modules\sales\app\DB\repositories\ProductRepository;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\services\cartitem\CreateCartItemAction;
use PostApi\modules\sales\domain\services\cartitem\DeleteCartItemAction;
use PostApi\modules\sales\domain\services\cartitem\GetCartItemCollectionAction;
use PostApi\modules\sales\domain\services\cartitem\GetCartItemItemAction;
use PostApi\modules\sales\domain\services\cartitem\UpdateCartItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class CartItemController implements ApiControllerContract
{
    #[Override]
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $cartItemRepository = new CartItemRepository();
            $critiria = [];

            if (isset($body['cart_id'])) {
                $critiria['cart_id'] = $body['cart_id'];
            }
            if (isset($body['product_id'])) {
                $critiria['product_id'] = $body['product_id'];
            }
           if (isset($body['quantity'])) {
                $critiria['quantity'] = $body['quantity'];
            } elseif (isset($body['greater_than_quantity'])) {
                $critiria['greater_than_quantity'] = $body['greater_than_quantity'];
            } elseif (isset($body['less_than_quantity'])) {;
                $critiria['less_than_quantity'] = $body['less_than_quantity'];
            } elseif (isset($body['greater_than_or_equal_quantity'])) {
                $critiria['greater_than_or_equal_quantity'] = $body['greater_than_or_equal_quantity'];
            } elseif (isset($body['less_than_or_equal_quantity'])) {
                $critiria['less_than_or_equal_quantity'] = $body['less_than_or_equal_quantity'];
            }

            if (!empty($critiria)) {
                $cartItems = $cartItemRepository->findBy($critiria);
            } else {
                $cartItems = $cartItemRepository->findAll();
            }

            $serin = GetCartItemCollectionAction::execute($cartItems);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display cart item error ", title: "incorrect paramter", status: true, detail: "internal server error", statusCode: 500);
        }
    }

    #[Override]
    public function get(string $id)
    {
        try {
            $serin = GetCartItemItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display cart item error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart item for this id", statusCode: 400);
        }
    }

    #[Override]
    public function create(Request $request)
    {        
        $params = $request->body;
        if (!isset($params['cart_id'], $params['product_id'], $params['quantity'])) {
            return ViewError::viewProplem("creating cart item error", "missing required paramters error", 1, "missing required paramters cart_id, product_id, quantity", 400);
        }
        try {
            $cartRepo = new CartRepository();
            $cart = $cartRepo->findOne($params['cart_id']);
            if (!$cart) {
                return ViewError::viewProplem("creating cart item error", "invalid cart_id", 1, "cart not found", 400);
            }
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("creating cart item error", "invalid product_id", 1, "product not found", 400);
            }
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity((float)$params['quantity']);           
            $createCartItemAction = new CreateCartItemAction();
            $cartItem = $createCartItemAction->execute($cartItem);
            $serin = GetCartItemItemAction::execute($cartItem->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create cart item error ", title: "incorrect paramter", status: true, detail: "unexpected error creating cart item", statusCode: 500);
        }
    }

    #[Override]
    public function update(Request $request,string $id)
    {       
        $params = $request->body;
        if (!isset($params['cart_id'], $params['product_id'], $params['quantity'])) {
            return ViewError::viewProplem("updating cart item error", "missing required paramters error", 1, "missing required paramters cart_id, product_id, quantity", 400);
        }
        try {
            $cartItemRepository = new CartItemRepository();
            $cartItem = $cartItemRepository->findOne((int)$id);
            if (!$cartItem) {
                return ViewError::viewProplem(type: "update cart item error ", title: "incorrect paramter", status: true, detail: "cart item not found", statusCode: 400);
            }
            $cartRepo = new CartRepository();
            $cart = $cartRepo->findOne($params['cart_id']);
            if (!$cart) {
                return ViewError::viewProplem("updating cart item error", "invalid cart_id", 1, "cart not found", 400);
            }
            $productRepo = new ProductRepository();
            $product = $productRepo->findOne($params['product_id']);
            if (!$product) {
                return ViewError::viewProplem("updating cart item error", "invalid product_id", 1, "product not found", 400);
            }
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity((float)$params['quantity']);            

            UpdateCartItemAction::execute($cartItem);
            http_response_code(200);
            return Json::toJson(["message" => "update cart item successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update cart item error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart item for this id", statusCode: 400);
        }
    }

    #[Override]
    public function delete(string $id)
    {
        try {
            $deleteCartItemAction = new DeleteCartItemAction();
            $deleteCartItemAction->execute((int)$id);
            http_response_code(200);
            return Json::toJson(["message" => "delete cart item successfuly"]);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete cart item error ", title: "incorrect paramter", status: true, detail: "there is no corresponding cart item for this id", statusCode: 400);
        }
    }
}
