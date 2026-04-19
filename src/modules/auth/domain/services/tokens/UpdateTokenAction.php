<?php

namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\shared\helpers\adapters\JWT;

class UpdateTokenAction
{
    public static function execute(int $tokenId, bool $is_revoked)
    {
        $tokenRepository = new TokenRepository();
        $userRepository = new UserRepository();
        $token = $tokenRepository->findOne($tokenId);  
         
        $user  = $userRepository->findOne($token->getUser()->getId());
        $token->setRevoked($is_revoked);
        $payload = [
            'user'=> [
                'id'=>$user->getId() ,
                'name'=>$user->getName() ,
                'email'=>$user->getEmail() ,
                'role'=>$user->getRole()->getName()
            ] ,
            'iat' => $token->getCreatedAt(),
            'exp' => $token->getExpiresAt() ,
            'is_revoked'=>$token->getRevoked()
        ];
        $jwtToken = JWT::encode($payload);
        $token->setToken($jwtToken);        
        $tokenRepository->update($token);
    }
}
