<?php

namespace PostApi\modules\sales\domain\services\lead;

use PostApi\modules\sales\app\DB\repositories\LeadRepository;

class DeleteLeadAction
{
    public static function execute(string $id)
    {
        $leadRepository = new LeadRepository();
        $lead = $leadRepository->findOne($id);
        if ($lead) {
            $leadRepository->delete($id);
        }
    }
}
