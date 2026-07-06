<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPrucherCollectionAction
{
    public static function execute()
    {
        $repo = new PrucherRepository();
        $pruchers = $repo->findAll();
        return SerializeToSerin::serializeCollection($pruchers);
    }
}
