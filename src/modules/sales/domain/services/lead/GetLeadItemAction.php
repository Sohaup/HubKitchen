<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetLeadItemAction
{
    public static function execute(string $id)
    {
        $leadRepository = new LeadRepository();
        $lead = $leadRepository->findOne($id);       
        $serin = SerializeToSerin::serialize($lead);
        return $serin;
    }
}
