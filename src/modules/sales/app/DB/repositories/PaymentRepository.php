<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\PaymentMapper;
use PostApi\modules\sales\domain\entities\Payment;
use PostApi\shared\templates\DB_Trait;

class PaymentRepository
{
    use DB_Trait;
    private PaymentMapper $paymentMapper;

    public function __construct()
    {
        $this->initialize();
        $this->paymentMapper = new PaymentMapper($this->dataBase);
    }

    public function find(int $id)
    {
        return $this->paymentMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->paymentMapper->findAll();
    }

    public function findBy(array $critiria)
    {
        return $this->paymentMapper->findBy($critiria);
    }

    public function create(Payment $payment)
    {
        $this->paymentMapper->create($payment);
    }

    public function update(Payment $payment)
    {
        $this->update($payment);
    }
    public function delete(int $id)
    {
        $this->paymentMapper->delete($id);
    }
}
