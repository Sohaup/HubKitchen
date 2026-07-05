<?php

namespace PostApi\modules\HR\domain\EntityListeners;

use Error;
use FFI\Exception;
use Override;
use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\Role;
use PostApi\modules\HR\domain\services\employee\CreateEmployeeAction;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\templates\DB_Trait;
use SplObserver;
use SplSubject;

class CreateEmployeeRoleListener implements SplObserver
{
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
    }
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateEmployeeAction && $subject->getEvent() == "created") {
            $roleRepo = new RoleRepository();
            $employee = $subject->getEmployee();
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($employee->getUser()->getId());
            try {
                $roleData = getRoleByName($this->queryBuilder, $employee->getJob()->getName());
                $role = $roleRepo->findOne($roleData['id']);
                $user->setRole($role);
                $userRepo->update($user);
            } catch (Error $error) {
                $role = new Role();
                $role->setName($employee->getJob()->getName());
                $roleRepo->create($role);                
                $user->setRole($role);
                $userRepo->update($user);
            }
        }
    }
}

function getRoleByName(QueryBuilder $queryBuilder, string $name)
{
    $queryTable = new QueryTable("auth.roles");
    $queryColumns = new QueryColumns(["id"]);
    $condition = new Condition("name", ConditionOperators::EQUAL, $name);
    $queryCondition = new BasicCondition($condition);
    $selectRoleWithNameQuery = new Select($queryTable->getQuery(), $queryColumns->getColumns(), $queryCondition->getCondition());
    $role = $queryBuilder->select($selectRoleWithNameQuery->getQuery(), $queryCondition->getValues(), PDO::FETCH_ASSOC);
    return $role[0];
}
