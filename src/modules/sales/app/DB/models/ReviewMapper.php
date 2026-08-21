<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\modules\sales\domain\entities\Review;

class ReviewMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getReviewQuery = $this->db->prepare("SELECT * FROM sales.review_view WHERE id = ?");
            $getReviewQuery->execute([$id]);
            $reviewRawData = $getReviewQuery->fetch(PDO::FETCH_ASSOC);
            if ($reviewRawData) {
                $customer = new Customer();
                $customer->setId($reviewRawData['customer_id']);
                $customer->setUserId($reviewRawData['user_id']);
                $customer->setStripeId($reviewRawData['customer_stripe_id']);
                $product = new Product();
                $product->setId($reviewRawData['product_id']);
                $product->setName($reviewRawData['name']);
                $product->setPrice($reviewRawData['price']);
                $product->setStripeId($reviewRawData['product_stripe_id']);
                $product->setImage($reviewRawData['image']);
                $product->setCreatedAt($reviewRawData['created_at']);
                $review = new Review();
                $review->setId($reviewRawData['id']);
                $review->setReview($reviewRawData['review']);
                $review->setCustomer($customer);
                $review->setProduct($product);
                $this->identityMap[$id] = $review;
                return $review;
            }
          
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll(): array
    {
        try {
            $getReviewsQuery = $this->db->prepare("SELECT * FROM sales.review_view ");
            $getReviewsQuery->execute([]);
            $reviewsRawData = $getReviewsQuery->fetchAll(PDO::FETCH_ASSOC);

            foreach ($reviewsRawData as $reviewRawData) {
                if (!isset($this->identityMap[$reviewRawData['id']])) {
                    $customer = new Customer();
                    $customer->setId($reviewRawData['customer_id']);
                    $customer->setUserId($reviewRawData['user_id']);
                    $customer->setStripeId($reviewRawData['customer_stripe_id']);
                    $product = new Product();
                    $product->setId($reviewRawData['product_id']);
                    $product->setName($reviewRawData['name']);
                    $product->setPrice($reviewRawData['price']);
                    $product->setStripeId($reviewRawData['product_stripe_id']);
                    $product->setImage($reviewRawData['image']);
                    $product->setCreatedAt($reviewRawData['created_at']);
                    $review = new Review();
                    $review->setId($reviewRawData['id']);
                    $review->setReview($reviewRawData['review']);
                    $review->setCustomer($customer);
                    $review->setProduct($product);
                    $this->identityMap[$reviewRawData['id']] = $review;
                }
            }

            return array_values($this->identityMap);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = [])
    {
        $query = "SELECT * FROM sales.review_view";
        $whereClauses = [];
        $bindings = [];

        if (isset($criteria['customer_id'])) {
            $whereClauses[] = "customer_id = ?";
            $bindings[] = $criteria['customer_id'];
        }

        if (isset($criteria['product_id'])) {
            $whereClauses[] = "product_id = ?";
            $bindings[] = $criteria['product_id'];
        }

        if (isset($criteria['review'])) {
            $whereClauses[] = "review = ?";
            $bindings[] = $criteria['review'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            $reviewsRawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($reviewsRawData as $reviewRawData) {
                $customer = new Customer();
                $customer->setId($reviewRawData['customer_id']);
                $customer->setUserId($reviewRawData['user_id']);
                $customer->setStripeId($reviewRawData['customer_stripe_id']);
                $product = new Product();
                $product->setId($reviewRawData['product_id']);
                $product->setName($reviewRawData['name']);
                $product->setPrice($reviewRawData['price']);
                $product->setStripeId($reviewRawData['product_stripe_id']);
                $product->setImage($reviewRawData['image']);
                $product->setCreatedAt($reviewRawData['created_at']);
                $review = new Review();
                $review->setId($reviewRawData['id']);
                $review->setReview($reviewRawData['review']);
                $review->setCustomer($customer);
                $review->setProduct($product);
                $this->identityMap[$reviewRawData['id']] = $review;
            }
            return array_values($this->identityMap);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Review $review)
    {
        try {
            $createReviewQuery = $this->db->prepare("INSERT INTO sales.reviews(review, customer_id, product_id) VALUES(?, ?, ?) RETURNING id");
            $createReviewQuery->execute([$review->getReview(), $review->getCustomer()->getId(), $review->getProduct()->getId()]);
            $reviewId = $createReviewQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $review->setId($reviewId);
            $this->identityMap[$review->getId()] = $review;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Review $review)
    {
        try {
            $updateReviewQuery = $this->db->prepare("UPDATE sales.reviews SET review = ?, customer_id = ?, product_id = ? WHERE id = ?");
            $updateReviewQuery->execute([$review->getReview(), $review->getCustomer()->getId(), $review->getProduct()->getId(), $review->getId()]);
            $this->identityMap[$review->getId()] = $review;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteReviewQuery = $this->db->prepare("DELETE FROM sales.reviews WHERE id = ?");
                $deleteReviewQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
