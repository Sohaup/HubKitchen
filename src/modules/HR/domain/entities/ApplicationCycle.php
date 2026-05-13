<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\helpers\types\AppraisalStatusType;

class ApplicationCycle
{
    private ?int $id;
    private string $name;
    private string $starts_at;
    private string $ends_at;
    private AppraisalStatusType $status;
    public function __construct(?int $id = null, string $name, string $starts_at, string $ends_at, AppraisalStatusType $status)
    {
        $this->id = $id ?? 0;
        $this->name = $name;
        $this->starts_at = $starts_at;
        $this->ends_at = $ends_at;
        $this->status = $status;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }

    public function setStartsAt(string $starts_at)
    {
        $this->starts_at = $starts_at;
    }
    public function getStartsAt()
    {
        return $this->starts_at;
    }

    public function setEndsAt(string $endsAt)
    {
        $this->ends_at = $endsAt;
    }
    public function getEndsAt()
    {
        return $this->ends_at;
    }

    public function setStatus(AppraisalStatusType $status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
}
