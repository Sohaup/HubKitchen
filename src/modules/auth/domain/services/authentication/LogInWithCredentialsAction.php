<?php

namespace PostApi\modules\auth\domain\services\authentication;

use Error;
use Exception;
use PDO;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\helpers\templates\LogInTemplate;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\queryBuilder\builder\QueryBuilder;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;

class LogInWithCredentialsAction extends LogInTemplate
{
  public function handleLogIn(): User
  {
    $request = new Request();
    $params = $request->body;
    $email = $params['email'];
    $password = $params['password'];
    $userRepository = new UserRepository();
    $db = $userRepository->getDbInstance();
    $queryBuilder = new QueryBuilder($db->pdo);
    $table = new QueryTable("auth.users");
    $condition = new Condition("email", ConditionOperators::EQUAL, $email);
    $conditionQuery = new BasicCondition($condition);
    $columns = new QueryColumns(['*']);
    $select = new Select(table: $table->getQuery(), columns: $columns->getColumns(), condition: $conditionQuery->getCondition());
    $userRow = $queryBuilder->select($select->getQuery(), $conditionQuery->getValues(), PDO::FETCH_ASSOC)[0];

    if (!$userRow) {
      throw new Exception("email or password is not correct ");
    }
    if (!password_verify($password, $userRow['password'])) {
      throw new Exception("email or password is not correct ");
    }
    $user = $userRepository->findOne($userRow['id']);
    return $user;
  }
}
