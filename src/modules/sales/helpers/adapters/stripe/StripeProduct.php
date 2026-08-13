<?php

namespace PostApi\modules\sales\helpers\adapters\stripe;

use Error;
use Exception;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\shared\config\Env;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeProduct
{
    private StripeClient $stripe;
    public function __construct()
    {
        Env::configureEnv();
        $this->stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }
    public function create(Product $product)
    {
        try {
            $product = $this->stripe->products->create(['name' => $product->getName(), 'default_price_data' => ['currency'=>'usd' , 'unit_amount'=>($product->getPrice()*100)]]);
            return $product;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $products = $this->stripe->products->all();
            return $products;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function find(string $stripeId)
    {
        try {
            $product = $this->stripe->products->retrieve($stripeId);
            return $product;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Product $product)
    {
        try {
            $product = $this->stripe->products->update($product->getStripeId(), ['active' => false, 'default_price' => $product->getPrice()]);
            return $product;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function delete(string $stripeId)
    {
        try {
            $product = $this->stripe->products->update($stripeId , ['active'=>false]);
            return $product;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }
}
