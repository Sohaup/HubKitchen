<?php

namespace PostApi\modules\manegers\domain\services\maneger;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetManegerItemAction
{
    public static function execute(string $id)
    {
        $repo = new ManegerRepository();
        $item = $repo->findOne($id);
        $serin = SerializeToSerin::serialize($item);
        return $serin;
    }
}
