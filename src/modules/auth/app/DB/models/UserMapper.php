<?php

namespace PostApi\modules\auth\app\DB\models;

use Error;
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
        try {
            if (!isset($this->identityMap[$id])) {
                $stmt = $this->db->prepare("SELECT u.* , r.name AS role_name FROM auth.users AS u LEFT JOIN auth.roles AS r ON u.role_id = r.id WHERE u.id = ?");
                $stmt->execute([$id]);
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $user = new User();
                $user->setId($id);
                $user->setName($userRow['name']);
                $user->setEmail($userRow['email']);
                $user->setPassword($userRow['password']);
                $user->setPhone($userRow['phone']);
                $user->setGoogleId($userRow['google_id']);
                $userRole = new Role();
                $userRole->setId($userRow['role_id']);
                $userRole->setName($userRow['role_name']);
                $user->setRole($userRole);
                $user->setAvatar($userRow['avatar']);
                $this->identityMap[$id] = $user;
                return $user;
            }

            return $this->identityMap[$id];
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    /**
     * @return User[]
     */
    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT u.* , r.name AS role_name FROM auth.users AS u LEFT JOIN auth.roles AS r ON u.role_id = r.id");
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
                    $userRole = new Role();
                    $userRole->setId($userRow['role_id']);
                    $userRole->setName($userRow['role_name']);
                    $user->setRole($userRole);
                    $user->setAvatar($userRow['avatar']);
                    $this->identityMap[$userRow['id']] = $user;
                }
            }
            return $this->identityMap;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }

    public function findBy(array $criteria = []): array
    {
        $query = "
        SELECT u.*, r.name AS role_name 
        FROM auth.users u
        LEFT JOIN auth.roles r ON u.role_id = r.id
    ";

        $whereClauses = [];
        $bindings = [];

        if (!empty($criteria['role_id'])) {
            $whereClauses[] = "u.role_id = ?";
            $bindings[] = $criteria['role_id'];
        }

        if (!empty($criteria['name'])) {
            $whereClauses[] = "u.name LIKE ?";
            $bindings[] = "%" . $criteria['name'] . "%";
        }

        if (!empty($criteria['email'])) {
            $whereClauses[] = "u.email = ?";
            $bindings[] = $criteria['email'];
        }

        if (!empty($criteria['phone'])) {
            $whereClauses[] = "u.phone = ?";
            $bindings[] = $criteria['phone'];
        }

        if (count($whereClauses) > 0) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($bindings);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($rows as $row) {
            $id = $row['id'];

            if (!isset($this->identityMap[$id])) {
                $this->identityMap[$id] = $this->findOne($id);
            }

            $results[] = $this->identityMap[$id];
        }

        return $results;
    }
    public function insert(User $user)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO auth.users(name , email , password , phone , role_id , avatar) VALUES(? , ? , ? , ? , ? , ? ) RETURNING id");
            $stmt->execute([$user->getName(), $user->getEmail(), $user->getPassword(), $user->getPhone(), $user->getRole()->getId(), $user->getAvatar()]);
            $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $user->setId($userId);
            $this->identityMap[$userId] = $user;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
    public function insertGoogleUser(User $user)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO auth.users(name , email , role_id , google_id , avatar) VALUES(? , ? , ? , ? , ?) RETURNING id");
            $stmt->execute([$user->getName(), $user->getEmail(), $user->getRole()->getId(), $user->getGoogleId(), $user->getAvatar()]);
            $userId = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            $user->setId($userId);
            $this->identityMap[$userId] = $user;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
    public function update(User $user)
    {
        try {
            $stmt = $this->db->prepare("UPDATE auth.users SET name = ? , email = ? , password = ? , phone = ? , role_id = ? , avatar = ? WHERE id = ?");
            $stmt->execute([$user->getName(), $user->getEmail(), $user->getPassword(), $user->getPhone(), $user->getRole()->getId(), $user->getAvatar(), $user->getId()]);
            $this->identityMap[$user->getId()] = $user;
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
    public function delete(string $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM auth.users WHERE id = ?");
            $stmt->execute([$id]);
            unset($this->identityMap[$id]);
        } catch (PDOException $err) {
            throw new Error($err->getMessage());
        }
    }
}
