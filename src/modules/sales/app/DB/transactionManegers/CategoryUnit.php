<?php
namespace PostApi\modules\sales\app\DB\transactionManagers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\CategoryMapper;
use PostApi\modules\sales\domain\entities\Category;

class CategoryUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private CategoryMapper $categoryMapper;
    public function __construct(private PDO $db) {
        $this->categoryMapper = new CategoryMapper($db);
    }

    public function registerNew(Category &$category)
    {
        if (!in_array($category, $this->newObjects, true)) {
            $this->newObjects[] = $category;
        }
    }
    public function registerDirty(Category &$category)
    {
        if (!in_array($category, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $category;
        }
    }
    public function registerDeleted(Category &$category)
    {
        if (!in_array($category, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $category;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->categoryMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->categoryMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->categoryMapper->delete($entity->getId());
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
