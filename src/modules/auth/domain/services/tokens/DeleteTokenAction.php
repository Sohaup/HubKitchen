<?php
namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;

class DeleteTokenAction {
    public static function execute(int $tokenId) {
        $tokenRepository = new TokenRepository();
        $tokenRepository->findOne($tokenId);
        $tokenRepository->delete($tokenId);
    }
}