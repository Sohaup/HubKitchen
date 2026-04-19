<?php
namespace PostApi\modules\auth\app\DB\repositories;

use Error;
use PostApi\modules\auth\app\DB\models\TokenMapper;
use PostApi\modules\auth\domain\Entities\Token;
use PostApi\shared\templates\DB_Trait;

class TokenRepository {
    private TokenMapper $tokenMapper;
    use DB_Trait;
    public function __construct()
    {
       $this->initialize();
       $this->tokenMapper = new TokenMapper($this->postgre->pdo);
    }
    public function findOne(int $id) {
        try {
            $token = $this->tokenMapper->findOne($id);
            return $token;
        } catch(Error $error) {
            throw new Error("no corrosponding token for this id");
        }
    }
    public function findAll() {
        $tokens = $this->tokenMapper->findAll();
        return $tokens;
    }
    public function create(Token $token) {
        $this->tokenMapper->create($token);
    }
    public function update(Token $token) {
        try {
            $this->tokenMapper->update($token);            
        } catch(Error $error) {
            throw new Error("no corrosponding token for this id");
        }
    }
    public function delete(int $id) {
        try {
            $this->tokenMapper->delete($id);            
        } catch(Error $error) {
            throw new Error("no corrosponding token for this id");
        }
    }
    
}