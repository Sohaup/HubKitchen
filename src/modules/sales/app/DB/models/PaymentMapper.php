<?php

namespace PostApi\modules\sales\app\DB\models;

use PDO;
use PostApi\modules\sales\domain\entities\Order;
use PostApi\modules\sales\domain\entities\Customer;
use PostApi\modules\sales\domain\entities\Cart;
use PostApi\modules\sales\domain\entities\Payment;

class PaymentMapper
{
    /** @var array<Payment> */
    private array $identityMap = [];

    public function __construct(private PDO $db) {}

    /** @return array<Payment> */
    public function findAll()
    {
        $getPaymentsQuery = $this->db->prepare("SELECT * FROM sales.payment_view");
        $getPaymentsQuery->execute([]);
        $paymentsRawData = $getPaymentsQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($paymentsRawData as $paymentRawData) {
            if (!isset($this->identityMap[$paymentRawData['id']])) {
                $order = new Order();
                $order->setId($paymentRawData['order_id']);
                $order->setCreatedAt($paymentRawData['order_created_at']);
                $customer = new Customer();
                $customer->setId($paymentRawData['customer_id']);
                $customer->setUserId($paymentRawData['user_id']);
                $customer->setStripeId($paymentRawData['stripe_id']);
                $order->setCustomer($customer);

                $payment = new Payment();
                $payment->setId($paymentRawData['id']);
                $payment->setAmount($paymentRawData['amount']);
                $payment->setCurrency($paymentRawData['currency']);
                $payment->setStatus($paymentRawData['status']);
                $payment->setOrder($order);
                $payment->setStripeSessionId($paymentRawData['stripe_session_id']);
                $payment->setStripePaymentIntentId($paymentRawData['stripe_payment_intent']);
                $payment->setCreatedAt($paymentRawData['created_at']);
                $payment->setUpdatedAt($paymentRawData['updated_at']);
                // Assuming CartMapper also uses a joined query
                $cart = new Cart();
                $cart->setId($paymentRawData['cart_id']); // Initialize with empty ID, you can fetch it later if needed
                $cart->setPrice($paymentRawData['cart_price']);
                $cart->setCreatedAt($paymentRawData['cart_created_at']);
                $cart->setUserId($paymentRawData['cart_user_id']);
                $order->setCart($cart);

                $this->identityMap[$paymentRawData['id']] = $payment;
            }
        }

        return array_values($this->identityMap);
    }

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }

        $getPaymentQuery = $this->db->prepare("SELECT * FROM sales.payment_view WHERE id = ?");
        $getPaymentQuery->execute([$id]);
        $paymentRawData = $getPaymentQuery->fetch(PDO::FETCH_ASSOC);

        if ($paymentRawData) {
            $order = new Order();
            $order->setId($paymentRawData['order_id']);
            $order->setCreatedAt($paymentRawData['order_created_at']);
            $customer = new Customer();
            $customer->setId($paymentRawData['customer_id']);
            $customer->setUserId($paymentRawData['user_id']);
            $customer->setStripeId($paymentRawData['stripe_id']);
            $order->setCustomer($customer);

            $payment = new Payment();
            $payment->setId($paymentRawData['id']);
            $payment->setAmount($paymentRawData['amount']);
            $payment->setCurrency($paymentRawData['currency']);
            $payment->setStatus($paymentRawData['status']);
            $payment->setOrder($order);
            $payment->setStripeSessionId($paymentRawData['stripe_session_id']);
            $payment->setStripePaymentIntentId($paymentRawData['stripe_payment_intent']);
            $payment->setCreatedAt($paymentRawData['created_at']);
            $payment->setUpdatedAt($paymentRawData['updated_at']);
            // Assuming CartMapper also uses a joined query
            $cart = new Cart();
            $cart->setId($paymentRawData['cart_id']);
            $cart->setPrice($paymentRawData['cart_price']);
            $cart->setCreatedAt($paymentRawData['cart_created_at']);
            $cart->setUserId($paymentRawData['cart_user_id']);
            $order->setCart($cart);

            $this->identityMap[$paymentRawData['id']] = $payment;
            return $payment;
        }
    }

    public function create(Payment $payment): void
    {
        $createPaymentQuery = $this->db->prepare("INSERT INTO sales.payments(amount, currency , status , stripe_session_id , stripe_payment_intent , order_id) VALUES(? , ? ,? ,? ,?, ?) RETURNING id");
        $createPaymentQuery->execute([$payment->getAmount(), $payment->getCurrency(), $payment->getStatus(), $payment->getStripeSessionId(), $payment->getStripePaymentIntentId(), $payment->getOrder()->getId()]);
        $paymentId = $createPaymentQuery->fetch(PDO::FETCH_ASSOC)['id'];
        $payment->setId($paymentId);
        $this->identityMap[$paymentId] = $payment;
    }

    public function update(Payment $payment): void
    {
        $updatePaymentQuery = $this->db->prepare("UPDATE sales.payments SET amount = ? , currency = ? , status = ? , stripe_session_id = ? , stripe_payment_intent = ? , order_id = ? WHERE id = ?");
        $updatePaymentQuery->execute([$payment->getAmount(), $payment->getCurrency(), $payment->getStatus(), $payment->getStripeSessionId(), $payment->getStripePaymentIntentId(), $payment->getOrder()->getId(), $payment->getId()]);
        $this->identityMap[$payment->getId()] = $payment;
    }

    public function delete(int $id): void
    {
        $deletePaymentQuery = $this->db->prepare("DELETE FROM sales.payments WHERE id = ?");
        $deletePaymentQuery->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
