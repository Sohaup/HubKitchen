<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Conditions;

use IteratorAggregate;
use Traversable;

class ConditionsCollection implements IteratorAggregate
{
    public array $conditions = [];
    public function __construct( array $conditions)
    {        
        $this->conditions = $conditions;
    }
    public function addCondition(ConditionUnit $condition)
    {
        $this->conditions[] = $condition;
    }
    /**
     * @return Traversable<ConditionUnit>
     **/
    public function getIterator(): Traversable
    {
        foreach ($this->conditions as $condition) {
            yield $condition;
        }
    }
}
