<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetManegerCollectionAction
{
    public static function execute()
    {
        $repo = new ManegerRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
