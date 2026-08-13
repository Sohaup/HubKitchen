<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use Error;
use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\PaymentMapper;
use PostApi\modules\sales\domain\entities\Payment;

class PaymentUnit
{
    /** @var array<Payment> */
    private array $newpayments = [];
    /** @var array<Payment> */
    private array $dirtyPaymnts = [];
    /** @var array<Payment> */
    private array $deletedPayments = [];
    private PaymentMapper $paymentMapper;

    public function __construct(private PDO $db) {
        $this->paymentMapper = new PaymentMapper($db); 
    }

    public function registerNew(Payment &$payment)
    {
        if (!in_array($payment, $this->newpayments)) {
            $this->newpayments[] = $payment;
        }
    }

    public function registerDirty(Payment &$payment)
    {
        if (!in_array($payment, $this->dirtyPaymnts)) {
            $this->dirtyPaymnts[] = $payment;
        }
    }

    public function registerDeleted(Payment &$payment)
    {
        if (!in_array($payment, $this->deletedPayments)) {
            $this->deletedPayments[] = $payment;
        }
    }

    public function commit()
    {
        try {
            $this->db->beginTransaction();
            foreach ($this->newpayments as $payment) {
                $this->paymentMapper->create($payment);
            }
            foreach ($this->dirtyPaymnts as $payment) {
                $this->paymentMapper->update($payment);
            }
            foreach ($this->deletedPayments as $payment) {
                $this->paymentMapper->delete($payment->getId());
            }
            $this->db->commit();
            $this->newpayments = [];
            $this->dirtyPaymnts = [];
            $this->deletedPayments = [];
        } catch (PDOException $error) {
            $this->db->rollBack();
            throw new Error($error->getMessage());
        }
    }
}
