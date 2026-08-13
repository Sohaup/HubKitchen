<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetReviewItemAction
{
    public static function execute(int $id)
    {
        $reviewRepository = new ReviewRepository();
        $review = $reviewRepository->findOne($id);
        $serin = SerializeToSerin::serialize($review);
        return $serin;
    }
}
