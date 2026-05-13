<?php

namespace PostApi\modules\HR\domain\entities;

class ApplicationTemplate
{
    private int $id = 0;
    private string $title;
    private string $description;
    public function __construct(?int $id = null, string $title, string $description)
    {
        $this->id = $id ?? 0;
        $this->title = $title;
        $this->description = $description;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
}
