<?php
namespace PostApi\modules\auth\domain\services\authentication;

use PDO;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
 
class GetGoogleUserAction {
    public static function execute(string $googleId) {
        $userRepository = new UserRepository();
        $queryBuilder = new QueryBuilder($userRepository->getDbInstance()->pdo);
        $queryTable = new QueryTable("auth.users");
        $queryColumns = new QueryColumns(['*']);
        $condition = new Condition("google_id" , ConditionOperators::EQUAL , $googleId);
        $queryCondition = new BasicCondition($condition);
        $selectQuery = new Select(table:$queryTable->getQuery() , columns:$queryColumns->getColumns() , condition:$queryCondition->getCondition());
        $googleUserData = $queryBuilder->select($selectQuery->getQuery() , $queryCondition->getValues() , PDO::FETCH_ASSOC);
        if ($googleUserData) {
            $googleUser = $userRepository->findOne($googleUserData[0]['id']);
            return $googleUser;
        } else {
            return false;
        }

    }
}