<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\ReviewMapper;
use PostApi\modules\sales\domain\entities\Review;

class ReviewUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private ReviewMapper $reviewMapper;
    public function __construct(private PDO $db)
    {
        $this->reviewMapper = new ReviewMapper($db);
    }

    public function registerNew(Review &$review)
    {
        if (!in_array($review, $this->newObjects, true)) {
            $this->newObjects[] = $review;
        }
    }
    public function registerDirty(Review &$review)
    {
        if (!in_array($review, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $review;
        }
    }
    public function registerDeleted(Review &$review)
    {
        if (!in_array($review, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $review;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->reviewMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->reviewMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->reviewMapper->delete($entity->getId());
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
