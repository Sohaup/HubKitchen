<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEvolutionCritiriaCollectionAction
{
    public static function execute()
    {
        $repo = new EvolutionCritiriaRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
