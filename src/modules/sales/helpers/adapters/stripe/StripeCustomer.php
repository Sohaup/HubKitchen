<?php

namespace PostApi\modules\sales\helpers\adapters\stripe;

use Error;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\shared\config\Env;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeCustomer
{
    private StripeClient $stripe;

    public function __construct()
    {
        Env::configureEnv();
        $this->stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    public function create(Customer $customer)
    {
        try {
            $stripeCustomer = $this->stripe->customers->create(['name' => $customer->getUser()->getName(), 'email' => $customer->getUser()->getEmail()]);
            return $stripeCustomer;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stripeCustomer = $this->stripe->customers->all();
            return $stripeCustomer;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function find(string $customerStripeId)
    {
        try {
            $stripeCustomer = $this->stripe->customers->retrieve($customerStripeId);
            return $stripeCustomer;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function update(Customer $customer)
    {
        try {
            $stripeCustomer = $this->stripe->customers->update($customer->getStripeId(), ['name' => $customer->getUser()->getName(), 'email' => $customer->getUser()->getEmail()]);
            return $stripeCustomer;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }

    public function delete(string $customerStripeId)
    {
        try {
            $stripeCustomer = $this->stripe->customers->delete($customerStripeId);
            return $stripeCustomer;
        } catch (ApiErrorException $error) {
            throw new Error($error->getMessage());
        }
    }
}
