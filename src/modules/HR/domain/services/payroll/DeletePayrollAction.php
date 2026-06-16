<?php

namespace PostApi\modules\HR\domain\services\payroll;

use Error;
use PostApi\modules\HR\app\DB\repositories\PayrollRepository;

class DeletePayrollAction
{
    public static function execute(int $id)
    {
        $repo = new PayrollRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $repo->delete($id);
    }
}
