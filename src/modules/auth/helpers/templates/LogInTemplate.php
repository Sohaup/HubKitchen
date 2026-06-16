<?php
namespace PostApi\modules\auth\helpers\templates;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\domain\services\tokens\CreateTokenAction;

abstract class LogInTemplate {
    private string $token;
    public function __construct()
    {
        $user = $this->handleLogIn();
        $this->token = $this->createToken($user->getId());
    }
    abstract public function handleLogIn() : User;  
    final public function createToken(string $userId) {
        $token = CreateTokenAction::execute($userId , true);
        return $token;
    }    
    public function getToken() {
        return $this->token;
    }
}