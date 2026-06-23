<?php

namespace PostApi\modules\CS\domain\entities;

use DateTime;

class CaseInterAction
{
    private ?int $id = 0;
    private Customer $customer;
    private Employee $employee;
    private Action $action;
    private Status $status;
    private Ticket $ticket;
    private DateTime $interactedAt;
    private string $takedAction;

    public function __construct()
    {
        $this->customer = new Customer();
        $this->employee = new Employee();
        $this->action = new Action();
        $this->status = new Status();
        $this->ticket = new Ticket();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }
    public function getCustomer()
    {
        return $this->customer;
    }
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
    }
    public function getEmployee()
    {
        return $this->employee;
    }
    public function setAction(Action $action)
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
    public function setStatus(Status $status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
    public function getTicket()
    {
        return $this->ticket;
    }
    public function setTakedAction(string $takedAction)
    {
        $this->takedAction = $takedAction;
    }
    public function getTakedAction()
    {
        return $this->takedAction;
    }
    public function setInteractedAt(DateTime $interactedAt)
    {
        $this->interactedAt = $interactedAt;
    }
    public function getInteractedAt()
    {
        return $this->interactedAt;
    }
}
