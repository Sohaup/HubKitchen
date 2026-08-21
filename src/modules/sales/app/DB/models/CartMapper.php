<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
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
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
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
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = [])
    {
        $query = "SELECT * FROM sales.cart";
        $whereClauses = [];
        $bindings = [];

        if (isset($criteria['user_id'])) {
            $whereClauses[] = "user_id = ?";
            $bindings[] = $criteria['user_id'];
        }

        if (isset($criteria['price'])) {
            $whereClauses[] = "price = ?";
            $bindings[] = $criteria['price'];
        } elseif (isset($criteria['greater_than_price'])) {
            $whereClauses[] = "price > ?";
            $bindings[] = $criteria['greater_than_price'];
        } elseif (isset($criteria['less_than_price'])) {
            $whereClauses[] = "price < ?";
            $bindings[] = $criteria['less_than_price'];
        } elseif (isset($criteria['greater_than_or_equal_price'])) {
            $whereClauses[] = "price >= ?";
            $bindings[] = $criteria['greater_than_or_equal_price'];
        } elseif (isset($criteria['less_than_or_equal_price'])) {
            $whereClauses[] = "price <= ?";
            $bindings[] = $criteria['less_than_or_equal_price'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $cart = new Cart();
                $cart->setId($row['id']);
                $cart->setPrice($row['price']);
                $cart->setCreatedAt($row['created_at']);
                $cart->setUserId($row['user_id']);
                $this->identityMap[$row['id']] = $cart;
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Cart $cart)
    {
        try {
            $createCartQuery = $this->db->prepare("INSERT INTO sales.cart(user_id, price) VALUES(?, ?) RETURNING id, created_at");
            $createCartQuery->execute([
                $cart->getUserId(),
                $cart->getPrice()
            ]);
            $row = $createCartQuery->fetch(PDO::FETCH_ASSOC);
            $cart->setId($row['id']);
            $cart->setCreatedAt($row['created_at']);
            $this->identityMap[$cart->getId()] = $cart;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Cart $cart)
    {
        try {
            $updateCartQuery = $this->db->prepare("UPDATE sales.cart SET user_id = ?, price = ? WHERE id = ?");
            $updateCartQuery->execute([
                $cart->getUserId(),
                $cart->getPrice(),
                $cart->getId()
            ]);
            $this->identityMap[$cart->getId()] = $cart;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteCartQuery = $this->db->prepare("DELETE FROM sales.cart WHERE id = ?");
                $deleteCartQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
