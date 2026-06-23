<?php

namespace PostApi\modules\HR\domain\entities;

use PostApi\modules\HR\domain\aggregators\Skills;

class JobDescription
{
    private ?int $id = 0;
    private string $name = "";
    private Skills $skills;
    private Shift $shift;    
    public function __construct()
    {
        $this->skills = new Skills();
        $this->shift = new Shift();
    }
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
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function addSkill(Skill $skill)
    {
        $this->skills->addSkill($skill);
    }
    public function removeSkill(Skill $skill)
    {
        $this->skills->deleteSkill($skill);
    }
    public function getSkills()
    {
        return $this->skills;
    }
    public function setShift(Shift $shift) {
        $this->shift = $shift;
    }
    public function getShift() {
        return $this->shift;
    }
}
