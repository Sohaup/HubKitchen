<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetReviewCollectionAction
{
    public static function execute()
    {
        $reviewRepository = new ReviewRepository();
        $reviews = $reviewRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($reviews);
        return $serin;
    }
}
