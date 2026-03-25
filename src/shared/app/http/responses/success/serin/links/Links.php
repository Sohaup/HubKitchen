<?php
namespace PostApi\shared\app\http\responses\success\serin\links;

use IteratorAggregate;
use Traversable;

class Links implements IteratorAggregate {
    public array $links = [];
    public function addLink(Link $link) {
        $this->links[] = $link;
    }
    /**
     * @return Traversable<Link>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->links as $link) {
            yield $link;
        }
    }
}