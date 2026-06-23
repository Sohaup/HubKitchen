<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetStatusItemAction
{
    public static function execute(string $id)
    {
        $repo = new StatusRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
