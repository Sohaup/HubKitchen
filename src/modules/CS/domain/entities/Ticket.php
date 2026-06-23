<?php

namespace PostApi\modules\CS\domain\entities;

use PostApi\modules\CS\domain\valueObjects\types\TicketType;

class Ticket
{
    private ?int $id = 0;
    private string $type = "";

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setType(string $type)
    {
        foreach (TicketType::cases() as $case) {
            if ($case->value == $type) {
                $this->type = $type;
            }
        }
    }
    public function getType()
    {
        return $this->type;
    }
}
