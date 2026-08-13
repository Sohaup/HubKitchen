<?php

namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTokenItemAction
{
    public static function execute(int $tokenId)
    {
        $tokenRepository = new TokenRepository();
       
        $token = $tokenRepository->findOne($tokenId);
        $serin = SerializeToSerin::serialize($token);
        return $serin;     
    }
}
