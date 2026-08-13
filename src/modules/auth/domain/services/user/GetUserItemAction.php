<?php

namespace PostApi\modules\auth\domain\services\user;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetUserItemAction
{
    public static function execute(User $user)
    {
        $serin = SerializeToSerin::serialize($user);
        return $serin;
    }
}
