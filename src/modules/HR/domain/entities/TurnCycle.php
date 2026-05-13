<?php
namespace PostApi\modules\HR\domain\entities;

class TurnCycle {
    private ?int $id;
    private string $start_at;
    private string $leave_at;
    private Employee $employee;
    public function __construct(?int $id = null,string $start_at , string $leave_at , Employee $employee )
    {
        $this->id = $id ?? 0;
        $this->start_at = $start_at;
        $this->leave_at = $leave_at;
        $this->$employee = $employee;
    }

    public function setId(int $id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function setStartAt(string $start_at) {
        $this->start_at = $start_at;
    }
    public function getStartAt() {
        return $this->start_at;
    }

    public function setLeaveAt(string $leave_at) {
        $this->leave_at = $leave_at;
    }
    public function getLeaveAt() {
        return $this->leave_at;
    }

    public function setEmployee(Employee $employee) {
        $this->employee = $employee;
    }
    public function getEmployee() {
        return $this->employee;
    }
}