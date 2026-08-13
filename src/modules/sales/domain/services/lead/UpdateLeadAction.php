<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;
use PostApi\modules\sales\domain\entities\Lead;

class UpdateLeadAction
{
    public static function execute(Lead $lead)
    {
        $leadRepository = new LeadRepository();
        $leadRepository->update($lead);
    }
}
