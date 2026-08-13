<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;
use PostApi\modules\sales\domain\entities\Offer;

class UpdateOfferAction
{
    public static function execute(Offer $offer)
    {
        $offerRepository = new OfferRepository();
        $offerRepository->update($offer);
    }
}
