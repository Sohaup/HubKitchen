<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\OfferMapper;
use PostApi\modules\sales\domain\entities\Offer;
use PostApi\shared\templates\DB_Trait;

class OfferRepository
{
    private OfferMapper $offerMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->offerMapper = new OfferMapper($this->dataBase);
    }

    public function findOne(int $id)
    {
        return $this->offerMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->offerMapper->findAll();
    }

    public function create(Offer $offer)
    {
        $this->offerMapper->create($offer);
    }

    public function update(Offer $offer)
    {
        $this->offerMapper->update($offer);
    }

    public function delete(int $id)
    {
        $this->offerMapper->delete($id);
    }
}
