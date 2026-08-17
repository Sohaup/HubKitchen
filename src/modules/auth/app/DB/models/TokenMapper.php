<?php

namespace PostApi\modules\auth\app\DB\models;

use Error;
use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\Token;

class TokenMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}

    public function findOne(int $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        try {
            $getTokenStmt = $this->db->prepare("SELECT * FROM auth.tokens WHERE id = ?");
            $getTokenStmt->execute([$id]);
            $tokenRow = $getTokenStmt->fetch(PDO::FETCH_ASSOC);
            $userRepository = new UserRepository();
            $user = $userRepository->findOne($tokenRow['user_id']);
            $token = new Token(id: $tokenRow['id'], user: $user, token: $tokenRow['token'], created_at: $tokenRow['created_at'], expires_at: $tokenRow['expires_at'], is_revoked: $tokenRow['is_revoked']);
            $this->identityMap[$id] = $token;
            return $token;
        } catch (Error $error) {
            throw new Error($error->getMessage());
        }
    }
    /**
     * @return Token[]
     */
    public function findAll()
    {
        try {
            $getTokensStmt = $this->db->prepare("SELECT * FROM auth.tokens");
            $getTokensStmt->execute([]);
            $userRepository = new UserRepository();
            $tokensRow = $getTokensStmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tokensRow as $tokenRow) {
                if (!isset($this->identityMap[$tokenRow['id']])) {
                    $userToken = $userRepository->findOne($tokenRow['user_id']);
                    $token = new Token(id: $tokenRow['id'], user: $userToken, token: $tokenRow['token'], created_at: $tokenRow['created_at'], expires_at: $tokenRow['expires_at'], is_revoked: $tokenRow['is_revoked']);
                    $this->identityMap[$tokenRow['id']] = $token;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function create(Token $token)
    {
        try {
            $createTokenStmt = $this->db->prepare("INSERT INTO auth.tokens(token , user_id , created_at , expires_at , is_revoked) VALUES(? , ? , ? , ? , ?) RETURNING id");
            $createTokenStmt->execute([$token->getToken(), $token->getUser()->getId(), $token->getCreatedAt()->format('Y-m-d H:i:s'), $token->getExpiresAt()->format('Y-m-d H:i:s'), $token->getRevoked() ? 1 : 0]);
            $tokenId = $createTokenStmt->fetch(PDO::FETCH_ASSOC)['id'];
            $token->setId($tokenId);
            $this->identityMap[$token->getId()] = $token;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function update(Token $token)
    {
        try {
            if (isset($this->identityMap[$token->getId()])) {
                $updateTokenStmt = $this->db->prepare("UPDATE auth.tokens SET token = ? , user_id = ? , is_revoked = ? WHERE id = ?");
                $updateTokenStmt->execute([$token->getToken(), $token->getUser()->getId(), $token->getRevoked() ? 1 : 0, $token->getId()]);
                $this->identityMap[$token->getId()] = $token;
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function delete(int $id)
    {
        try {
            if (isset($this->identityMap[$id])) {
                $deleteTokenStmt = $this->db->prepare("DELETE FROM auth.tokens WHERE id = ?");
                $deleteTokenStmt->execute([$id]);
                unset($this->identityMap[$id]);
            }
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
