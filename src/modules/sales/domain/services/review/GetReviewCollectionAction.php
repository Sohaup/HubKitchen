<?php

namespace PostApi\modules\sales\domain\services\review;

use PostApi\modules\sales\app\DB\repositories\ReviewRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetReviewCollectionAction
{
    public static function execute(array $items = null)
    {
        $reviewRepository = new ReviewRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $reviewRepository->findAll());
        return $serin;
    }
}
