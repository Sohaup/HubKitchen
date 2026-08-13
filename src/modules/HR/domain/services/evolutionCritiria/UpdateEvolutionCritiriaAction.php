<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use Error;
use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;

class UpdateEvolutionCritiriaAction
{
    public static function execute(int $id , array $body)
    {
        $repo = new EvolutionCritiriaRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }       
        if (isset($body['critiria'])) {
            $entity->setCritiria($body['critiria']);
        }
        if (isset($body['weight'])) {
            $entity->setWeight((int)$body['weight']);
        }
        if (isset($body['template_id'])) {
            $templateRepo = new ApplicationTemplateRepository();
            $entity->setTemplate($templateRepo->findOne((int)$body['template_id']));
        }

        $repo->update($entity);
    }
}
