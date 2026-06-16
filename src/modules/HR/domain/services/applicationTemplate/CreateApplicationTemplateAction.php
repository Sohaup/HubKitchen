<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\modules\HR\domain\entities\ApplicationTemplate;
use PostApi\shared\app\http\requests\Request;

class CreateApplicationTemplateAction
{
    public static function execute()
    {
        $repo = new ApplicationTemplateRepository();
        $request = new Request();
        $body = $request->body;
        $title = $body['title'] ?? '';
        $description = $body['description'] ?? '';
        $entity = new ApplicationTemplate(null, $title, $description);
        $repo->create($entity);
        return $entity;
    }
}
