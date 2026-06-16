<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;

use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSaleryComponentCollectionAction
{
    public static function execute()
    {
        $repo = new SaleryComponentRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
