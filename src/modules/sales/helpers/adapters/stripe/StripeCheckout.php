<?php

namespace PostApi\modules\sales\helpers\adapters\stripe;

use Error;
use PostApi\modules\sales\domain\entities\Payment;
use PostApi\modules\sales\domain\entities\Product;
use PostApi\shared\config\Env;
use PostApi\shared\helpers\fecade\Urls;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCheckout
{
    private StripeClient $stripe;
    public function __construct()
    {
        Env::configureEnv();
        $this->stripe = new StripeClient([
          'api_key'=>  $_ENV['STRIPE_SECRET_KEY'] ,
          'api_base'=> "https://api.stripe.com"
        ]);
        
    }

    public function create(Payment $payment)
    {
        try {
            $order = $payment->getOrder();
            $cart = $order->getCart();
            $cartItems = $cart->getCartItems();
            /** @var array<Product>  */
            $products = [];
            $quantities = [];
            foreach ($cartItems as $cartItem) {
                $products[] = $cartItem->getProduct();
                $quantities[] = $cartItem->getQuantity();
            }
            $stripeLineItems = [];
            foreach ($products as $index => $product) {
                $price = (int)floor(($product->getPrice() * $quantities[$index]) * 100);
                // echo $price;
                // echo "\n";
                // echo $quantities[$index];
                // echo "\n";
                $stripeLineItems[] = [
                    'price_data' => [
                        'currency' => $payment->getCurrency(),
                        'product_data' => [
                            'name' => $product->getName(),
                        ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => 1
                ];
            }
            $stripeSession = $this->stripe->checkout->sessions->create([
                'line_items' => $stripeLineItems,
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'success_url' => Urls::transformRouteUrl("/checkouts/success"),
                'cancel_url' => Urls::transformRouteUrl("/checkouts/failed")
            ]);
            return $stripeSession;
        } catch (ApiErrorException $error) {
            echo $error->getLine();
            throw new Error($error->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $checkouts = $this->stripe->checkout->sessions->all();
            return $checkouts;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function findOne(string $stripeSessionId)
    {
        try {
            $checkout = $this->stripe->checkout->sessions->retrieve($stripeSessionId);
            return $checkout;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function delete(string $stripeSessionId)
    {
        try {
            $checkout = $this->stripe->checkout->sessions->expire($stripeSessionId);
            return $checkout;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }
}
