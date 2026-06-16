<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;

use Error;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;

class DeleteSaleryComponentAction
{
    public static function execute(int $id)
    {
        $repo = new SaleryComponentRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $repo->delete($id);
    }
}
