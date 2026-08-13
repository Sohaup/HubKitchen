<?php

namespace PostApi\modules\sales\domain\services\offer;

use PostApi\modules\sales\app\DB\repositories\OfferRepository;

class DeleteOfferAction
{
    public static function execute(int $id)
    {
        $offerRepository = new OfferRepository();
        $offer = $offerRepository->findOne($id);
        if ($offer) {
            $offerRepository->delete($id);
        }
    }
}
