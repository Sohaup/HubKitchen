<?php

namespace PostApi\modules\sales\app\DB\repositories;

use PostApi\modules\sales\app\DB\models\ReviewMapper;
use PostApi\modules\sales\domain\entities\Review;
use PostApi\shared\templates\DB_Trait;

class ReviewRepository
{
    private ReviewMapper $reviewMapper;
    use DB_Trait;

    public function __construct()
    {
        $this->initialize();
        $this->reviewMapper = new ReviewMapper($this->dataBase);
    }

    public function findOne(int $id)
    {
        return $this->reviewMapper->findOne($id);
    }

    public function findAll()
    {
        return $this->reviewMapper->findAll();
    }

    public function create(Review $review)
    {
        $this->reviewMapper->create($review);
    }

    public function update(Review $review)
    {
        $this->reviewMapper->update($review);
    }

    public function delete(int $id)
    {
        $this->reviewMapper->delete($id);
    }
}
