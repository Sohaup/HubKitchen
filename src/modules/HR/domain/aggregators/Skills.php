<?php

namespace PostApi\modules\HR\domain\aggregators;

use IteratorAggregate;
use PostApi\modules\HR\domain\entities\Skill;
use Traversable;

class Skills implements IteratorAggregate
{
    private array $skills = [];
    public function addSkill(Skill $skill)
    {
        $this->skills[] = $skill;
    }
    public function deleteSkill(Skill $skill) {
        $index = array_search($skill , $this->skills);
        if ($index) {
            unset($this->skills[$index]);
        }
    } 
    public function getIterator(): Traversable
    {
       foreach($this->skills as $skill) {
            yield $skill;
       }
    }
}
