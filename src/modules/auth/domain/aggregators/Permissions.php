<?php
namespace PostApi\modules\auth\domain\aggregators;

use IteratorAggregate;
use PostApi\modules\auth\domain\Entities\Permission;
use Traversable;

class Permissions implements IteratorAggregate
{
    public array $permissions = [];

    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
    }
    /**
     * @return Traversable<Permission>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->permissions as $permission) {
            yield $permission;
        }
    }
}
