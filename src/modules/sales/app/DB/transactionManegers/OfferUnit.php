<?php

namespace PostApi\modules\sales\app\DB\transactionManegers;

use PDO;
use PDOException;
use PostApi\modules\sales\app\DB\models\OfferMapper;
use PostApi\modules\sales\domain\entities\Offer;

class OfferUnit
{
    private array $newObjects = [];
    private array $dirtyObjects = [];
    private array $deletedObjects = [];
    private OfferMapper $offerMapper;
    public function __construct(private PDO $db) {
        $this->offerMapper = new OfferMapper($db);
    }

    public function registerNew(Offer &$offer)
    {
        if (!in_array($offer, $this->newObjects, true)) {
            $this->newObjects[] = $offer;
        }
    }
    public function registerDirty(Offer &$offer)
    {
        if (!in_array($offer, $this->dirtyObjects, true)) {
            $this->dirtyObjects[] = $offer;
        }
    }
    public function registerDeleted(Offer &$offer)
    {
        if (!in_array($offer, $this->deletedObjects, true)) {
            $this->deletedObjects[] = $offer;
        }
    }

    public function commit()
    {
        $this->db->beginTransaction();
        try {
            foreach ($this->newObjects as $entity) {
                $this->offerMapper->create($entity);
            }
            foreach ($this->dirtyObjects as $entity) {
                $this->offerMapper->update($entity);
            }
            foreach ($this->deletedObjects as $entity) {
                $this->offerMapper->delete($entity->getId());
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
