<?php

namespace PostApi\modules\manegers\domain\entityListeners;

use Override;
use PDO;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\Role;
use PostApi\modules\manegers\domain\services\maneger\CreateManegerAction;
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

class CreateManegerListener implements SplObserver
{
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
    }

    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateManegerAction && $subject->getEvent() == "created") {
            $maneger = $subject->getManeger();
            $roleRepo = new RoleRepository();
            $userRepo = new UserRepository();
            $user = $userRepo->findOne($maneger->getUser()->getId());
            $roleData = getRoleByName($this->queryBuilder);
            if (!empty($roleData)) {
                $role = $roleRepo->findOne($roleData['id']);
                $user->setRole($role);
                $userRepo->update($user);
                return;
            }
            $role = new Role();
            $role->setName("maneger");
            $roleRepo->create($role);
            $user->setRole($role);
            $userRepo->update($user);
        }
    }
}



function getRoleByName(QueryBuilder $queryBuilder, string $name = "maneger")
{
    $queryTable = new QueryTable("auth.roles");
    $queryColumns = new QueryColumns(["id"]);
    $condition = new Condition("name", ConditionOperators::EQUAL, $name);
    $queryCondition = new BasicCondition($condition);
    $selectRoleWithNameQuery = new Select($queryTable->getQuery(), $queryColumns->getColumns(), $queryCondition->getCondition());
    $role = $queryBuilder->select($selectRoleWithNameQuery->getQuery(), $queryCondition->getValues(), PDO::FETCH_ASSOC);
    return $role[0];
}
