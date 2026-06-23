<?php

namespace PostApi\modules\CS\domain\services\caseinteract;

use PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository;

class DeleteCaseInterActionAction
{
    public static function execute(string $id)
    {
        $repo = new CaseInterActionRepository();
        $repo->delete($id);
    }
}
