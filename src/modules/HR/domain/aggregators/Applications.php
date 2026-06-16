<?php

namespace PostApi\modules\HR\domain\aggregators;

use IteratorAggregate;
use PostApi\modules\HR\domain\entities\Application;
use Traversable;

class Applications implements IteratorAggregate
{
    private array $applications = [];
    public function addApplication(Application $application)
    {
        $this->applications[] = $application;
    }
    public function deleteApplication(Application $application)
    {
        $index = array_search($application, $this->applications);
        if ($index) {
            unset($this->applications[$index]);
        }
    }
    public function getIterator(): Traversable
    {
        foreach ($this->applications as $application) {
            yield $application;
        }
    }
}
