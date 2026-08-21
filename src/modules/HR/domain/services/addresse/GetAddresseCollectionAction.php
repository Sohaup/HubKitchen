<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetAddresseCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new AddreseRepository();
        return SerializeToSerin::serializeCollection($items ?? $repo->findAll());
    }
}
