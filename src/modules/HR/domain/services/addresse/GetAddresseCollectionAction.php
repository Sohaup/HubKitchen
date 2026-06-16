<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAddresseCollectionAction
{
    public static function execute()
    {
        $repo = new AddreseRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
