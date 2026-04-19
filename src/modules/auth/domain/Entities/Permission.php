<?php
namespace PostApi\modules\auth\domain\Entities;

class Permission {
    private int $id = 0;
    private string $name = "";
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
}