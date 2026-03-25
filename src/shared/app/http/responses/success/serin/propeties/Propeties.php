<?php 
namespace PostApi\shared\app\http\responses\success\serin\propeties;
use IteratorAggregate;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use Traversable;

class Propeties implements IteratorAggregate  {
    public array $propeties = [];
    public function addPropety(Propety $propety) {
        $this->propeties[$propety->name] = $propety->value; 
    }
    /**
    **  @return  Traversable<Propety>
    **/
    public function getIterator(): Traversable
    {
       foreach ($this->propeties as $propety) {
            yield $propety;
       }
    }
}