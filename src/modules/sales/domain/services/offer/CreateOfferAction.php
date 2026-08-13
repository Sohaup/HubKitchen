<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\modules\sales\domain\entities\Offer;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateOfferAction
{
    public static function execute(Offer $offer)
    {
        $offerRepository = new OfferRepository();
        $offerRepository->create($offer);
        $serin = SerializeToSerin::serialize($offer);
        return $serin;
    }
}
