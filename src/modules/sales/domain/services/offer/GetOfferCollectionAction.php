<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetOfferCollectionAction
{
    public static function execute(array $items)
    {
        $offerRepository = new OfferRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $offerRepository->findAll());
        return $serin;
    }
}
