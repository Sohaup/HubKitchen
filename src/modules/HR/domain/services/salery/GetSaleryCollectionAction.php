<?php

namespace PostApi\modules\HR\domain\services\salery;

use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSaleryCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new SaleryRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
       
    }
}
