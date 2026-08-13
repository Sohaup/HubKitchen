<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetLeadCollectionAction
{
    public static function execute()
    {
        $leadRepository = new LeadRepository();
        $leads = $leadRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($leads);
        return $serin;
    }
}
