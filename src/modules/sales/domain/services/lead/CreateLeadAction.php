<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\modules\sales\domain\entities\Lead;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateLeadAction
{
    public static function execute(Lead $lead)
    {
        $leadRepository = new LeadRepository();
        $leadRepository->create($lead);
        $serin = SerializeToSerin::serialize($lead);
        return $serin;
    }
}
