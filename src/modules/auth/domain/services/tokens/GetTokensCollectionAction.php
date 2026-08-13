<?php

namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTokensCollectionAction
{
    public static function execute()
    {
        $tokensRepository = new TokenRepository();
        $tokens = $tokensRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($tokens);
        return $serin;
    }
}
