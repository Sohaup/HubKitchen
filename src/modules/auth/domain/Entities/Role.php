<?php
namespace PostApi\modules\auth\domain\Entities;

use PostApi\modules\auth\domain\aggregators\Permissions;

class Role {
    private ?int $id = 0;
    private string $name = "";
    private Permissions $permissions;
    public function __construct()
    {
        $this->permissions = new Permissions();
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    public function getPermissions() {
        return $this->permissions;
    }
    public function addPermission(Permission $permission) {
        $this->permissions->addPermission($permission);
    }
    public function removePermission(Permission $permission) {
        foreach ($this->permissions as $index=>$permissionItem) {
            if ($permissionItem->getId() == $permission->getId()) {
                unset($this->permissions[$index]);
            }
        }
    }
}