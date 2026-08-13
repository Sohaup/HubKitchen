<?php

namespace PostApi\modules\auth\domain\services\user;


use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetUsersCollectionAction
{
    /** * @param User[] */
    public static function execute(array $users)
    {
        $serin = SerializeToSerin::serializeCollection($users);
        return $serin;
    }
}
