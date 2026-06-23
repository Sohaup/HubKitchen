<?php

namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\CS\domain\valueObjects\types\StatusType;

class Status
{
    private ?int $id = 0;
    private string $status = "";
    private string $issued_at = "";

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setStatus(string $status)
    {
        foreach (StatusType::cases() as $case) {
            if ($case->value == $status) {
                $this->status = $status;
            }
        }
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setIssuedAt(string $issued_at)
    {
        $this->issued_at = $issued_at;
    }
    public function getIssuedAt()
    {
        return $this->issued_at;
    }
}
