<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\modules\sales\domain\entities\Review;

class UpdateReviewAction
{
    public static function execute(Review $review)
    {
        $reviewRepository = new ReviewRepository();
        $reviewRepository->update($review);
    }
}
