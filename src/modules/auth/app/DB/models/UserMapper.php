<?php

namespace PostApi\modules\auth\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\auth\domain\Entities\Role;
use PostApi\modules\auth\domain\Entities\User;

class UserMapper
{
    private array $identityMap = [];
    public function __construct(private PDO $db) {}
    public function findOne(string $id)
    {
        if (!isset($this->identityMap[$id])) {
            $stmt = $this->db->prepare("SELECT * FROM auth.users WHERE id = ?");
            $stmt->execute([$id]);
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User();
            $user->setId($id);
            $user->setName($userRow['name']);
            $user->setEmail($userRow['email']);
            $user->setPassword($userRow['password']);
            $user->setPhone($userRow['phone']);
            $user->setGoogleId($userRow['google_id']);            
            $getUserRoleStmt = $this->db->prepare("SELECT * FROM auth.roles WHERE id = ? ");
            $getUserRoleStmt->execute([$userRow['role_id']]);
            $userRoleRow = $getUserRoleStmt->fetch(PDO::FETCH_ASSOC);
            $userRole = new Role();
            $userRole->setId($userRoleRow['id']);
            $userRole->setName($userRoleRow['name']);
            $user->setRole($userRole);
            return $user;
        }

        return $this->identityMap[$id];
    }
    /**
     * @return User[]
     */
    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM auth.users");
        $stmt->execute([]);
        $usersRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($usersRow as $userRow) {
            if (!isset($this->identityMap[$userRow['id']])) {
                $user = new User();
                $user->setId($userRow['id']);
                $user->setName($userRow['name']);
                $user->setEmail($userRow['email']);
                $user->setPassword($userRow['password']);
                $user->setPhone($userRow['phone']);
                $user->setGoogleId($userRow['google_id']);
                $getUserRoleStmt = $this->db->prepare("SELECT * FROM auth.roles WHERE id = ? ");
                $getUserRoleStmt->execute([$userRow['role_id']]);
                $userRoleRow = $getUserRoleStmt->fetch(PDO::FETCH_ASSOC);
                $userRole = new Role();
                $userRole->setId($userRoleRow['id']);
                $userRole->setName($userRoleRow['name']);
                $user->setRole($userRole);
                $this->identityMap[$userRow['id']] = $user;
            }
        }
        return $this->identityMap;
    }
    public function insert(User $user)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO auth.users(name , email , password , phone , role_id ) VALUES(? , ? , ? , ? , ? ) RETURNING id");
            $stmt->execute([$user->getName(), $user->getEmail(), $user->getPassword(), $user->getPhone(), $user->getRole()->getId() ]);
            $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $user->setId($userId);
            $this->identityMap[$userId] = $user;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
    public function insertGoogleUser(User $user) {
        try {
            $stmt = $this->db->prepare("INSERT INTO auth.users(name , email , role_id , google_id) VALUES(? , ? , ? , ? ) RETURNING id");
            $stmt->execute([$user->getName(), $user->getEmail(), $user->getRole()->getId() , $user->getGoogleId()]);
            $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $user->setId($userId);
            $this->identityMap[$userId] = $user;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
    public function update(User $user)
    {
        $stmt = $this->db->prepare("UPDATE auth.users SET name = ? , email = ? , password = ? , phone = ? , role_id = ? WHERE id = ?");
        $stmt->execute([$user->getName(), $user->getEmail(), $user->getPassword(), $user->getPhone(), $user->getRole()->getId() , $user->getId()  ]);
        $this->identityMap[$user->getId()] = $user;
    }
    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM auth.users WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }    
}
