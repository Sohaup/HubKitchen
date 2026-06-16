<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use Error;
use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;

class DeleteEvolutionCritiriaAction
{
    public static function execute(int $id)
    {
        $repo = new EvolutionCritiriaRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $repo->delete($id);
    }
}
