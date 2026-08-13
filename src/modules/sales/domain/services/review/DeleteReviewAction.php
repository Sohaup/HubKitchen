<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;

class DeleteReviewAction
{
    public static function execute(int $id)
    {
        $reviewRepository = new ReviewRepository();
        $review = $reviewRepository->findOne($id);
        if ($review) {
            $reviewRepository->delete($id);
        }
    }
}
