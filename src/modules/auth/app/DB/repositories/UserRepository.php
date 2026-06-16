<?php
namespace PostApi\modules\auth\app\DB\repositories; 

use PostApi\modules\auth\app\DB\models\UserMapper;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\templates\DB_Trait;

class UserRepository
{
   private UserMapper $userMapper; 
   use DB_Trait;  
   public function __construct()
   {
        $this->initialize();
        $this->userMapper = new UserMapper($this->postgre->pdo);
   } 
    /** @return User */
    public function findOne(string $id) {
        $user = $this->userMapper->findOne($id);
        return $user;
    }
    public function findAll() {
        $users = $this->userMapper->findAll();
        return $users;
    }
    public function create(User $user) {
        $this->userMapper->insert($user);
    }
    public function createGoogleUser(User $user) {
        $this->userMapper->insertGoogleUser($user);
    }
    public function update(User $user) {
       $this->userMapper->update($user);
    }
    public function delete(string $id) {
        $this->userMapper->delete($id);
    }       

}
