<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;

class CreateApplicationTemplateAction
{
    public static function execute(array $body)
    {
        $repo = new ApplicationTemplateRepository();        
        $title = $body['title'] ?? '';
        $description = $body['description'] ?? '';
        $entity = new ApplicationTemplate(null, $title, $description);
        $repo->create($entity);
        return $entity;
    }
}
