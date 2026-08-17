<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\modules\sales\domain\entities\CartItem;
use PostApi\modules\sales\domain\entities\Product;

class OrderMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getOrderQuery = $this->db->prepare("SELECT * FROM sales.order_view  
            WHERE id = ?");
        $getOrderQuery->execute([$id]);
        $row = $getOrderQuery->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $order = new Order();
            $order->setId($row['id']);
            $order->setCreatedAt($row['created_at']);
            $customer = new Customer();
            $customer->setId($row['customer_id']);
            $customer->setUserId($row['user_id']);
            $customer->setStripeId($row['customer_stripe_id']);
            $order->setCustomer($customer);
            $cart = new Cart();
            $cart->setId($row['cart_id']);
            $cart->setPrice($row['cart_price']);
            $cart->setCreatedAt($row['cart_created_at']);
            $cart->setUserId($row['cart_user_id']);
            if ($row['product_id']) {
                $cartItem = new CartItem();
                $cartItem->setId($row['cart_item_id']);
                $cartItem->setQuantity($row['quantity']);
                $cartItem->setAddedAt($row['added_at']);
                $product = new Product();
                $product->setId($row['product_id']);
                $product->setName($row['name']);
                $product->setPrice($row['price']);
                $product->setStripeId($row['product_stripe_id']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['product_created_at']);
                $cartItem->setProduct($product);
                $cart->addCartItem($cartItem);
            }
            $order->setCart($cart);
            $this->identityMap[$row['id']] = $order;
            return $order;
        }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
        
    }

    public function findAll(): array
    {
        try {
            $getOrdersQuery = $this->db->prepare("SELECT * FROM sales.order_view ");
            $getOrdersQuery->execute([]);
            $rows = $getOrdersQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $order = new Order();
                    $order->setId($row['id']);
                    $order->setCreatedAt($row['created_at']);
                    $customer = new Customer();
                    $customer->setId($row['customer_id']);
                    $customer->setUserId($row['user_id']);
                    $customer->setStripeId($row['customer_stripe_id']);
                    $order->setCustomer($customer);
                    $cart = new Cart();
                    $cart->setId($row['cart_id']);
                    $cart->setPrice($row['cart_price']);
                    $cart->setCreatedAt($row['cart_created_at']);
                    $cart->setUserId($row['cart_user_id']);
                    if ($row['product_id']) {
                        $cartItem = new CartItem();
                        $cartItem->setId($row['cart_item_id']);
                        $cartItem->setQuantity($row['quantity']);
                        $cartItem->setAddedAt($row['added_at']);
                        $product = new Product();
                        $product->setId($row['product_id']);
                        $product->setName($row['name']);
                        $product->setPrice($row['price']);
                        $product->setStripeId($row['product_stripe_id']);
                        $product->setImage($row['image']);
                        $product->setCreatedAt($row['product_created_at']);
                        $cartItem->setProduct($product);
                        $cart->addCartItem($cartItem);
                    }

                    $order->setCart($cart);
                    $this->identityMap[$row['id']] = $order;
                }
            }

            return array_values($this->identityMap);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Order $order): void
    {
        try {
            $createOrderQuery = $this->db->prepare("INSERT INTO sales.orders(customer_id, cart_id) VALUES(?, ?) RETURNING id, created_at");
            $createOrderQuery->execute([
                $order->getCustomer()->getId(),
                $order->getCart()->getId()
            ]);
            $row = $createOrderQuery->fetch(PDO::FETCH_ASSOC);
            $order->setId($row['id']);
            $order->setCreatedAt($row['created_at']);
            $this->identityMap[$order->getId()] = $order;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Order $order): void
    {
        try {
            $updateOrderQuery = $this->db->prepare("UPDATE sales.orders SET customer_id = ?, cart_id = ? WHERE id = ?");
            $updateOrderQuery->execute([
                $order->getCustomer()->getId(),
                $order->getCart()->getId(),
                $order->getId()
            ]);
            $this->identityMap[$order->getId()] = $order;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id): void
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteOrderQuery = $this->db->prepare("DELETE FROM sales.orders WHERE id = ?");
                $deleteOrderQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
