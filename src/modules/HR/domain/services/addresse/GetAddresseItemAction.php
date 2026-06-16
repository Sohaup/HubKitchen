<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAddresseItemAction
{
    public static function execute(int $id)
    {
        $repo = new AddreseRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
