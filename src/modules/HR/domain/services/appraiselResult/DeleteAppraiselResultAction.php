<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use Error;
use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;

class DeleteAppraiselResultAction
{
    public static function execute(int $id)
    {
        $repo = new AppraiselResultRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $repo->delete($id);
    }
}
