<?php

namespace PostApi\modules\manegers\domain\entities;

use PostApi\modules\auth\domain\Entities\User;

class Maneger
{
    private ?string $id = "";
    private int $rank = 0;
    private User $user;
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
    public function setUser(User $user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }
    public function setDepartment(Department $department) {
        $this->department = $department;
    }
    public function getDepartment() {
        return $this->department;
    }
}
