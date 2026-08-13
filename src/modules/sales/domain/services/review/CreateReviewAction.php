<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\modules\sales\domain\entities\Review;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateReviewAction
{
    public static function execute(Review $review)
    {
        $reviewRepository = new ReviewRepository();
        $reviewRepository->create($review);
        $serin = SerializeToSerin::serialize($review);
        return $serin;
    }
}
