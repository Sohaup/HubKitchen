<?php

namespace PostApi\modules\CS\domain\entities;

class Action
{
    private ?int $id = 0;
    private string $action = "";
    private string $taked_at = "";

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setAction(string $action)
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
    public function setTakedAt(string $taked_at)
    {
        $this->taked_at = $taked_at;
    }
    public function getTakedAt()
    {
        return $this->taked_at;
    }
}
