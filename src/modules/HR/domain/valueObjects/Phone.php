<?php
namespace PostApi\modules\HR\domain\valueObjects;

use Exception;
use PostApi\shared\helpers\fecade\Validate;

class Phone
{
    public string $phone;

    public function validatePhone(string $phone)
    {
        try {
            $this->phone = Validate::validateValue( FILTER_VALIDATE_REGEXP, $phone,
                [
                    'options' => [
                        'regexp' => '/^[01]{1}[(0|1|2|5)]{1}[0-9]{9}$/'
                    ]
                ],
                "phone must be an egyption phone number"
            );
        } catch (Exception $error) {
            throw $error;
        }
    }

    public function getPhone() {
        return $this->phone;
    }
}