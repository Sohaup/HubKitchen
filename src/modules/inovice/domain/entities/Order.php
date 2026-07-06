<?php

namespace PostApi\modules\inovice\domain\entities;

use DateTime;

class Order
{
    private string $id;
    private Prucher $prucher;
    private DateTime $createdAt;

    public function setId(string $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setPrucher(Prucher $prucher)
    {
        $this->prucher = $prucher;
    }
    public function getPrucher()
    {
        return $this->prucher;
    }
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = new DateTime($createdAt);
    }
    public function getCreatedAt()
    {
        return $this->createdAt->format("r");
    }
}
