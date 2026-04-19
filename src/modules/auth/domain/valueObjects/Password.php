<?php 
namespace PostApi\modules\auth\domain\valueObjects;

use Exception;
use PostApi\shared\helpers\fecade\Validate;

class Password {
    private string $password;
    public function validatePassword(string $password) {
      try {
            $this->password = Validate::validateValue(FILTER_VALIDATE_REGEXP , $password , [
                'options'=> [
                    'regexp'=> '/^[A-Z]{1}[A-Za-z0-9]{1,20}[^a-zA-Z0-9]{1}$/' 
                ]
            ] , "password must start with capital caharcter followed by any nuber of chars and digits and ends with symbol with min leght of 3 chars and max length of 22 chars");
      } catch(Exception $error) {
             throw new Exception($error->getMessage());
      }
    }

    public function getPassword() {
        return $this->password;
    }

    public function encryptPassword() {
        $this->password = password_hash($this->password , \PASSWORD_BCRYPT);
    }
}