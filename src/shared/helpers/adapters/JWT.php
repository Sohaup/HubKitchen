<?php

namespace PostApi\shared\helpers\adapters;

use Error;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use PostApi\shared\config\Env;

class JWT
{
    public static function encode(array $payload)
    {
        try {
            Env::configureEnv();
            $jwtToken = FirebaseJWT::encode($payload, $_ENV['JWT_TOKEN_SECRET'], 'HS256');
            return $jwtToken;
        } catch (Error $error) {
            throw new Error($error->getMessage());
        }
    }

    public static function decode(string $jwtToken)
    {
        try {
            Env::configureEnv();
            $decodedJwtToken = FirebaseJWT::decode($jwtToken, new Key($_ENV['JWT_TOKEN_SECRET'], 'HS256'));
            return  $decodedJwtToken;
        } catch (Error $error) {
            throw new Error($error->getMessage());
        }
    }
}
