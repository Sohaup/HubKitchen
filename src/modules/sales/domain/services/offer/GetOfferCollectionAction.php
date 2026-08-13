<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOfferCollectionAction
{
    public static function execute()
    {
        $offerRepository = new OfferRepository();
        $offers = $offerRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($offers);
        return $serin;
    }
}
