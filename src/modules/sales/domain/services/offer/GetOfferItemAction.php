<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOfferItemAction
{
    public static function execute(int $id)
    {
        $offerRepository = new OfferRepository();
        $offer = $offerRepository->findOne($id);
        $serin = SerializeToSerin::serialize($offer);
        return $serin;
    }
}
