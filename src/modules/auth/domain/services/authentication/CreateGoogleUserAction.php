<?php

namespace PostApi\modules\auth\domain\services\authentication;

use League\OAuth2\Client\Provider\GoogleUser;
use PDO;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;

class CreateGoogleUserAction
{
    public static function execute(GoogleUser $googleUserData)
    {
        $userRepository = new UserRepository();
        $roleRepository = new RoleRepository();
        $queryBuilder = new QueryBuilder($userRepository->getDbInstance()->pdo);
        $table = new QueryTable("auth.roles");
        $columns = new QueryColumns(["id"]);
        $condition = new Condition("name", ConditionOperators::EQUAL, "user");
        $queryCondition = new BasicCondition($condition);
        $getUserRoleQuery =  new Select(table: $table->getQuery(), columns: $columns->getColumns(), condition: $queryCondition->getCondition());
        $userRoleId = $queryBuilder->select($getUserRoleQuery->getQuery(), $queryCondition->getValues(), PDO::FETCH_ASSOC)[0]["id"];
        $googleUser = new User();
        $googleUser->setName($googleUserData->getName());
        $googleUser->setEmail($googleUserData->getEmail());
        $googleUser->setGoogleId($googleUserData->getId());
        $role = $roleRepository->findOne($userRoleId);
        $googleUser->setRole($role);
        $userRepository->createGoogleUser($googleUser);
        return $googleUser;
    }
}
