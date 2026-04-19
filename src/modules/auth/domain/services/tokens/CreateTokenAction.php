<?php

namespace PostApi\modules\auth\domain\services\tokens;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\Token;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class CreateTokenAction
{
    public static function execute(string $userId , ?bool $withReturn = false)
    {
        try {
            $tokenRepository = new TokenRepository();
            $userRepository = new UserRepository();
            $roleRepository = new RoleRepository();            
            $user = $userRepository->findOne($userId);
            $role = $roleRepository->findOne($user->getRole()->getId());
            $token = new Token();
            $token->setUser($user);
            $payload = [
                'user' => [
                    'id'=>$user->getId() ,
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()->getName()
                ],
                'iat' => (int)$token->getCreatedAt()->format('U'),
                'exp' => (int)$token->getExpiresAt()->format('U'),
                'is_revoked' => $token->getRevoked()
            ];
            $jwtToken = JWT::encode($payload);
            $token->setToken($jwtToken);
            $tokenRepository->create($token);
            if ($withReturn) {
                return $jwtToken;
            }
        } catch (Exception $error) {
            return ViewError::viewProplem("create token error", "paramter error", 1, "there is no corrosponding user for this user_id", 400);
        }
    }
}
