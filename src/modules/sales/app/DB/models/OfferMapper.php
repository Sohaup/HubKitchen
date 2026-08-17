<?php

namespace PostApi\modules\sales\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\domain\entities\Offer;
use PostApi\modules\sales\domain\entities\Product;

class OfferMapper
{
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getOfferQuery = $this->db->prepare("SELECT o.id, o.value, p.id AS product_id, p.name, p.price, p.stripe_id, p.image, p.created_at
            FROM sales.offers o
            JOIN sales.products p ON o.product_id = p.id
            WHERE o.id = ?");
            $getOfferQuery->execute([$id]);
            $row = $getOfferQuery->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $offer = new Offer();
                $offer->setId($row['id']);
                $offer->setValue((float)$row['value']);
                $product = new Product();
                $product->setId($row['product_id']);
                $product->setName($row['name']);
                $product->setPrice((float)$row['price']);
                $product->setStripeId($row['stripe_id']);
                $product->setImage($row['image']);
                $product->setCreatedAt($row['created_at']);
                $offer->setProduct($product);
                $this->identityMap[$row['id']] = $offer;
                return $offer;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $getOffersQuery = $this->db->prepare("SELECT o.id, o.value, p.id AS product_id, p.name, p.price, p.stripe_id, p.image, p.created_at
            FROM sales.offers o
            JOIN sales.products p ON o.product_id = p.id");
            $getOffersQuery->execute([]);
            $rows = $getOffersQuery->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                if (!isset($this->identityMap[$row['id']])) {
                    $offer = new Offer();
                    $offer->setId($row['id']);
                    $offer->setValue((float)$row['value']);
                    $product = new Product();
                    $product->setId($row['product_id']);
                    $product->setName($row['name']);
                    $product->setPrice((float)$row['price']);
                    $product->setStripeId($row['stripe_id']);
                    $product->setImage($row['image']);
                    $product->setCreatedAt($row['created_at']);
                    $offer->setProduct($product);
                    $this->identityMap[$row['id']] = $offer;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function create(Offer $offer)
    {
        try {
            $createOfferQuery = $this->db->prepare("INSERT INTO sales.offers(product_id, value) VALUES(?, ?) RETURNING id");
            $createOfferQuery->execute([
                $offer->getProduct()->getId(),
                $offer->getValue()
            ]);
            $id = $createOfferQuery->fetch(PDO::FETCH_ASSOC)['id'];
            $offer->setId($id);
            $this->identityMap[$offer->getId()] = $offer;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function update(Offer $offer)
    {
        try {
            $updateOfferQuery = $this->db->prepare("UPDATE sales.offers SET product_id = ?, value = ? WHERE id = ?");
            $updateOfferQuery->execute([
                $offer->getProduct()->getId(),
                $offer->getValue(),
                $offer->getId()
            ]);
            $this->identityMap[$offer->getId()] = $offer;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteOfferQuery = $this->db->prepare("DELETE FROM sales.offers WHERE id = ?");
                $deleteOfferQuery->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
