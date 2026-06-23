<?php

namespace PostApi\modules\CS\domain\services\customerLog;

use Override;
use PostApi\modules\CS\app\DB\repositories\CustomerLogRepository;
use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\modules\CS\domain\entities\CustomerLog;
use PostApi\modules\CS\domain\entityListeners\CreateCustomerLogListener;
use PostApi\shared\app\http\requests\Request;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreateCustomerLogAction implements SplSubject
{
    private string $customerLogCreateEvent = "";
    private CustomerLog $customerLog;
    private SplObjectStorage $observers;
    public function __construct()
    {
       $this->customerLog = new CustomerLog();
       $this->observers = new SplObjectStorage();
       $this->attach(new CreateCustomerLogListener());
    }
    public function execute(): CustomerLog
    {
        $request = new Request();
        $params = $request->body;
        $customerId = $params['customer_id'] ?? null;
        $logType = $params['log_type'] ?? '';
        $createdAt = $params['created_at'] ?? date('c');
        $customerRepo = new CustomerRepository();
        $customer = $customerRepo->findOne($customerId);
        $log = new CustomerLog();
        $log->setCustomer($customer);
        $log->setLogType($logType);
        $log->setCreatedAt($createdAt);
        $repo = new CustomerLogRepository();
        $repo->create($log);
        $this->customerLogCreateEvent = "created";
        $this->customerLog = $log;
        $this->notify();
        return $log;
    }

    #[Override]
    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    #[Override]
    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    #[Override]
    public function notify(): void
    {
        foreach($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getCustomerLogEvent() {
        return $this->customerLogCreateEvent;
    } 

    public function getCustomerLog() {
        return $this->customerLog;
    }
}
