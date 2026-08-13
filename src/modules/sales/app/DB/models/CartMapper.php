<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\entities\Product;

class CartMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getCartQuery = $this->db->prepare("SELECT * FROM sales.cart WHERE id = ?");
        $getCartQuery->execute([$id]);
        $row = $getCartQuery->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $cart = new Cart();
            $cart->setId($row['id']);
            $cart->setPrice($row['price']);
            $cart->setCreatedAt($row['created_at']);
            $cart->setUserId($row['user_id']);

            $getCartItemsQuery = $this->db->prepare("SELECT * FROM sales.cart_item_view WHERE cart_id = ?");
            $getCartItemsQuery->execute([$row['id']]);
            $itemsRaw = $getCartItemsQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($itemsRaw as $row) {
                $cartItem = new CartItem();
                $cartItem->setId($row['id']);
                $cartItem->setQuantity($row['quantity']);
                $cartItem->setAddedAt($row['added_at']);

                $product = new Product();
                $product->setId($row['product_id']);
                $product->setName($row['name']);
                $product->setPrice($row['price']);
                $product->setStripeId($row['stripe_id']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['created_at']);

                $cartItem->setProduct($product);

                $cart = new Cart();
                $cart->setId($row['cart_id']);
                $cart->setUserId($row['user_id']);
                $cart->setPrice($row['price']);
                $cart->setCreatedAt($row['created_at']);
              
                $cart->addCartItem($cartItem);
            }
            $this->identityMap[$row['id']] = $cart;
            return $cart;
        }
    }

    public function findAll()
    {
        $getCartsQuery = $this->db->prepare("SELECT * FROM sales.cart ");
        $getCartsQuery->execute([]);
        $rows = $getCartsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $cart = new Cart();
                $cart->setId($row['id']);
                $cart->setPrice($row['price']);
                $cart->setCreatedAt($row['created_at']);
                $cart->setUserId($row['user_id']);

                $getCartItemsQuery = $this->db->prepare("SELECT * FROM sales.cart_item_view WHERE cart_id = ?");
                $getCartItemsQuery->execute([$row['id']]);
                $itemsRaw = $getCartItemsQuery->fetchAll(PDO::FETCH_ASSOC);
                foreach ($itemsRaw as $itemRow) {
                    $cartItem = new CartItem();
                    $cartItem->setId($itemRow['id']);
                    $cartItem->setQuantity($itemRow['quantity']);
                    $cartItem->setAddedAt($itemRow['added_at']);
                   
                    $product = new Product();
                    $product->setId($itemRow['product_id']);
                    $product->setName($itemRow['name']);
                    $product->setPrice($itemRow['price']);
                    $product->setStripeId($itemRow['stripe_id']);
                    $product->setImage($itemRow['image']);
                    $product->setCreatedAt($itemRow['created_at']);

                    $cartItem->setProduct($product);

                    $cart = new Cart();
                    $cart->setId($itemRow['cart_id']);
                    $cart->setUserId($itemRow['user_id']);
                    $cart->setPrice($itemRow['price']);
                    $cart->setCreatedAt($itemRow['created_at']);
                 
                    $cart->addCartItem($cartItem);
                }

                $this->identityMap[$row['id']] = $cart;
            }
        }
        return array_values($this->identityMap);
    }

    public function create(Cart $cart)
    {
        $createCartQuery = $this->db->prepare("INSERT INTO sales.cart(user_id, price) VALUES(?, ?) RETURNING id, created_at");
        $createCartQuery->execute([
            $cart->getUserId(),
            $cart->getPrice()
        ]);
        $row = $createCartQuery->fetch(PDO::FETCH_ASSOC);
        $cart->setId($row['id']);
        $cart->setCreatedAt($row['created_at']);
        $this->identityMap[$cart->getId()] = $cart;
    }

    public function update(Cart $cart)
    {
        $updateCartQuery = $this->db->prepare("UPDATE sales.cart SET user_id = ?, price = ? WHERE id = ?");
        $updateCartQuery->execute([
            $cart->getUserId(),
            $cart->getPrice(),
            $cart->getId()
        ]);
        $this->identityMap[$cart->getId()] = $cart;
    }

    public function delete(string $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteCartQuery = $this->db->prepare("DELETE FROM sales.cart WHERE id = ?");
            $deleteCartQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
