<?php

namespace PostApi\modules\inovice\domain\services\prucher;

use PostApi\modules\inovice\app\DB\repositories\PrucherRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPrucherItemAction
{
    public static function execute(string $id)
    {
        $repo = new PrucherRepository();
        $prucher = $repo->findOne($id);
        return SerializeToSerin::serialize($prucher);
    }
}
