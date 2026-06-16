<?php

namespace PostApi\modules\HR\domain\services\salery;

use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSaleryCollectionAction
{
    public static function execute()
    {
        $repo = new SaleryRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
