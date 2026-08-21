<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;

use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetSaleryComponentCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new SaleryComponentRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
     
    }
}
