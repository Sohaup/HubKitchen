<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use Error;
use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateEvolutionCritiriaAction
{
    public static function execute(int $id)
    {
        $repo = new EvolutionCritiriaRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }

        $request = new Request();
        $body = $request->body;

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
