<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetLeadCollectionAction
{
    public static function execute(array $items = null)
    {
        $leadRepository = new LeadRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $leadRepository->findAll());
        return $serin;
    }
}
