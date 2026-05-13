<?php

namespace PostApi\modules\HR\domain\entities;

use DateTime;

class Shift
{
    private ?int $id;
    private string $shiftName;
    private DateTime $startTime;
    private DateTime $endTime;
    private int $breakDuration;
    private bool $isOverNight;
    private bool $isActive;   
    private string $createdAt;

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setShiftName(string $shiftName)
    {
        $this->shiftName = $shiftName;
    }
    public function getShiftName()
    {
        return $this->shiftName;
    }
    public function setStartTime(DateTime $startTime)
    {
        $this->startTime = $startTime;
    }
    public function getStartTime()
    {
        return $this->startTime;
    }
    public function setEndTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
    }
    public function getEndTime()
    {
        return $this->endTime;
    }
    public function setBreakDuration(int $breakDuration)
    {
        $this->breakDuration = $breakDuration;
    }
    public function getBreakDuration()
    {
        return $this->breakDuration;
    }
    public function setIsOverNight(bool $isOverNight)
    {
        $this->isOverNight = $isOverNight;
    }
    public function getIsOverNight()
    {
        return $this->isOverNight;
    }
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }
    public function getIsActive()
    {
        return $this->isActive;
    }   
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
