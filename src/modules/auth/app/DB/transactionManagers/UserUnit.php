<?php

namespace PostApi\modules\auth\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\auth\app\DB\models\UserMapper;
use PostApi\modules\auth\domain\Entities\User;

class UserUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    public function __construct(private UserMapper $userMapper, private PDO $db) {}
    public function registerNew(User &$user)
    {
        if (!in_array($user, $this->newObjects , true)) {
            $this->newObjects[] = $user;
        }
    }
    public function registerDirty(User &$user)
    {
        if (!in_array($user, $this->dirtyObjects , true)) {
            $this->dirtyObjects[] = $user;
        }
    }
    public function registerDeleted(User &$user)
    {
        if (!in_array($user, $this->deletedObjects , true)) {
            $this->deletedObjects[] = $user;
        }
    }
    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->userMapper->insert($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->userMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->userMapper->delete($entity->getId());
            }
            $this->db->commit();
            $this->newObjects = [];
            $this->dirtyObjects = [];
            $this->deletedObjects = [];
        } catch (PDOException $error) {
            $this->db->rollBack();
            echo $error->getMessage();
        }
    }
}
