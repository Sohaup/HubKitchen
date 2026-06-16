<?php

namespace PostApi\modules\HR\domain\services\evolutionCritiria;

use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\modules\HR\domain\entities\EvolutionCritiria;
use PostApi\shared\app\http\requests\Request;

class CreateEvolutionCritiriaAction
{
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;

        $critiria = $body['critiria'] ?? '';
        $weight = isset($body['weight']) ? (int)$body['weight'] : 0;
        $templateId = $body['template_id'] ?? null;

        $templateRepo = new ApplicationTemplateRepository();
        $template = $templateRepo->findOne((int)$templateId);

        $entity = new EvolutionCritiria(null, $critiria, $weight, $template);

        $repo = new EvolutionCritiriaRepository();
        $repo->create($entity);
        return $entity;
    }
}
