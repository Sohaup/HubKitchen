<?php

namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTokensCollectionAction
{
    public static function execute(array $body)
    {
        $tokensRepository = new TokenRepository();
        $critiria = [];
        $tokens = [];
        if (isset($body['id'])) {
            $critiria['id'] = $body['id'];
        }
        if (isset($body['user_id'])) {
            $critiria['user_id'] = $body['user_id'];
        }
        if (isset($body['is_revoked'])) {
            $critiria['is_revoked'] = $body['is_revoked'];
        }
        if (!empty($critiria)) {
            $tokens = $tokensRepository->findBy($critiria);
        } else {
            $tokens = $tokensRepository->findAll();
        }
        $serin = SerializeToSerin::serializeCollection($tokens);
        return $serin;
    }
}
