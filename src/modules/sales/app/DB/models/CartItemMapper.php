<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\entities\Product;

class CartItemMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getCartItemQuery = $this->db->prepare("SELECT * FROM sales.cart_item_view  WHERE id = ?");
        $getCartItemQuery->execute([$id]);
        $row = $getCartItemQuery->fetch(PDO::FETCH_ASSOC);
        if ($row) {
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

            $cartItem->setCart($cart);

            $this->identityMap[$row['id']] = $cartItem;
            return $cartItem;
        }
    }

    public function findAll()
    {
        $getCartItemsQuery = $this->db->prepare("SELECT * FROM sales.cart_item_view ");

        $getCartItemsQuery->execute([]);
        $rows = $getCartItemsQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
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
                $cart->setPrice($row['cart_total_price']);
                $cart->setCreatedAt($row['cart_created_at']);

                $cartItem->setCart($cart);

                $this->identityMap[$row['id']] = $cartItem;
            }
        }
        return $this->identityMap;
    }

    public function create(CartItem $cartItem)
    {
        $createCartItemQuery = $this->db->prepare("INSERT INTO sales.cart_items(cart_id, product_id, quantity) VALUES(?, ?, ?) RETURNING id");

        $createCartItemQuery->execute([
            $cartItem->getCart()->getId(),
            $cartItem->getProduct()->getId(),
            $cartItem->getQuantity()
        ]);
        $id = $createCartItemQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $cartItem->setId($id);
        $this->identityMap[$cartItem->getId()] = $cartItem;
    }

    public function update(CartItem $cartItem)
    {
        $updateCartItemQuery = $this->db->prepare("UPDATE sales.cart_items SET cart_id = ?, product_id = ?, quantity = ? WHERE id = ?");
        $addedAt = $cartItem->getAddedAt();
        $updateCartItemQuery->execute([
            $cartItem->getCart()->getId(),
            $cartItem->getProduct()->getId(),
            $cartItem->getQuantity(),
            $cartItem->getId()
        ]);
        $this->identityMap[$cartItem->getId()] = $cartItem;
    }

    public function delete(int $id)
    {
        if (isset($this->identityMap[$id])) {
            $deleteCartItemQuery = $this->db->prepare("DELETE FROM sales.cart_items WHERE id = ?");
            $deleteCartItemQuery->execute([$id]);
            unset($this->identityMap[$id]);
        }
    }
}
