<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEvolutionCritiriaItemAction
{
    public static function execute(int $id)
    {
        $repo = new EvolutionCritiriaRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
