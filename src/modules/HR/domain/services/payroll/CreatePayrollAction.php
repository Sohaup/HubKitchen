<?php

namespace PostApi\modules\HR\domain\services\payroll;

use Override;
use PostApi\modules\HR\app\DB\repositories\PayrollRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\modules\HR\domain\entities\PayrollJournal;
use PostApi\modules\HR\domain\EntityListeners\CreatePayRollJournalListener;
use PostApi\shared\app\http\requests\Request;
use SplObjectStorage;
use SplObserver;
use SplSubject;

class CreatePayrollAction implements SplSubject
{
    private SplObjectStorage $observers;
    private string $createPayrollEvent;
    private PayrollJournal $payroll;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
        $this->attach(new CreatePayRollJournalListener());
    }

    public function execute()
    {
        $request = new Request();
        $body = $request->body;
        $employeeId = $body['employee_id'] ?? null;
        $componentId = $body['salery_component_id'] ?? null;
        $amount = (float)($body['amount'] ?? 0);
        $date = $body['date'] ?? date('Y-m-d');

        $employeeRepo = new EmployeeRepository();
        $employee = $employeeRepo->findOne($employeeId);
        $componentRepo = new SaleryComponentRepository();
        $component = $componentRepo->findOne($componentId);

        $entity = new PayrollJournal(null, $employee, $component, $amount, $date);
        $repo = new PayrollRepository();
        $repo->create($entity);
        $this->payroll = $entity;
        $this->createPayrollEvent = "created";
        $this->notify();
        return $entity;
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
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    public function getCreatePayrollEvent(): string
    {
        return $this->createPayrollEvent;
    }
    public function getPayroll(): PayrollJournal
    {
        return $this->payroll;
    }
}
