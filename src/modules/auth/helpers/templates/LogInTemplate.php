<?php
namespace PostApi\modules\auth\helpers\templates;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\domain\services\tokens\CreateTokenAction;
use PostApi\shared\app\http\requests\Request;

abstract class LogInTemplate {
    private string $token;
    public function __construct(Request $request)
    {
        $user = $this->handleLogIn($request->body);
        $this->token = $this->createToken($user->getId());
    }
    abstract public function handleLogIn(array $params) : User;  
    final public function createToken(string $userId) {
        $token = CreateTokenAction::execute($userId , true);
        return $token;
    }    
    public function getToken() {
        return $this->token;
    }
}