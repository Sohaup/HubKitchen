<?php
namespace PostApi\modules\HR\domain\entities;

class Skill {
    private ?int $id = 0;
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