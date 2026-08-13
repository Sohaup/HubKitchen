<?php

namespace PostApi\modules\manegers\domain\entities;


class Maneger
{
    private ?string $id = "";
    private int $rank = 0;
    private string $userId = "";
    private Department $department;

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setRank(int $rank) {
        $this->rank = $rank;
    }   
    public function getRank() {
        return $this->rank;
    }
    public function setUserId(string $userId) {
        $this->userId = $userId;
    }
    public function getUserId() {
        return $this->userId;
    }
    public function setDepartment(Department $department) {
        $this->department = $department;
    }
    public function getDepartment() {
        return $this->department;
    }
}
