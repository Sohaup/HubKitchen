<?php

namespace PostApi\modules\auth\domain\services\user;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\domain\valueObjects\Email;
use PostApi\modules\auth\domain\valueObjects\Password;
use PostApi\modules\auth\domain\valueObjects\Phone;

class ValidateUserAction
{
    /**
     * @return User
     */
    public static function execute(string $name, string $email, string $password, string $phone )
    {
        $user = new User();      
        $user->setName($name);
        $userEmail = new Email();
        $userEmail->validateEmail($email);
        $user->setEmail($userEmail->getEmail());
        $userPassword = new Password();
        $userPassword->validatePassword($password);
        $userPassword->encryptPassword();
        $user->setPassword($userPassword->getPassword());
        $userPhone = new Phone();
        $userPhone->validatePhone($phone);
        $user->setPhone($userPhone->getPhone());   
        return $user;
    }
}
