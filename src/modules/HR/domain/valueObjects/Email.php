<?php
namespace PostApi\modules\HR\domain\valueObjects;

use Exception;
use PostApi\shared\helpers\fecade\Validate;

class Email {
    private string $email;
    public function validateEmail(string $email) {
        try {
          $this->email = Validate::validateValue(type:FILTER_VALIDATE_EMAIL , value:$email , options:[] , message:"email is not valid");
        } catch(Exception $error) {
            throw new Exception($error->getMessage());
        }        
    }
    public function getEmail() {
        return $this->email;
    }
}