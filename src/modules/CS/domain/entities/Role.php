<?php

namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\CS\domain\valueObjects\types\RoleType;

class Role
{
    private ?int $id = 0;
    private string $name = "";

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
        foreach (RoleType::cases() as $case) {
            if ($case->value == $name) {
                $this->name = $name;
            }
        }
    }

    public function getName() {
        return $this->name;
    }
}
