<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;

class DeleteAddresseAction
{
    public static function execute(int $id)
    {
        $repo = new AddreseRepository();
        $entity = $repo->findOne($id);
        if ($entity) {
            $repo->delete($id);
        }
    }
}
