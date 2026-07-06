<?php
namespace PostApi\modules\inovice\domain\entities;

class Supplier {
    private string $id;
    private string $name;
    
    public function setId(string $id) {
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