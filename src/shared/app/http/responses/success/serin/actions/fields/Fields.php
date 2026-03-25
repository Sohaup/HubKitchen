<?php 
namespace PostApi\shared\app\http\responses\success\serin\actions\fields;

use IteratorAggregate;
use Traversable;

class Fields implements IteratorAggregate {
    public array $fields  = [];
    public function addField(Field $field) {
        $this->fields[] = $field;
    }
    /**
     * @return Traversable<Field>
     **/
    public function getIterator(): Traversable
    {
        foreach ($this->fields as $field) {
            yield $field;
        }
    }
}