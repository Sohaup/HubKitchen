<?php 

namespace PostApi\shared\app\http\responses\success\serin\enities;

use IteratorAggregate;
use Traversable;

class Entities implements IteratorAggregate {
    public array $entities = [];   
    public function addEntity(Entity $entity ) {
        $this->entities[] = $entity; 
    }
    /**
     * @return Traversable<Entity>
     **/
    public function getIterator(): Traversable
    {
        foreach ($this->entities as $entity) {
            yield $entity;
        }
    }
}