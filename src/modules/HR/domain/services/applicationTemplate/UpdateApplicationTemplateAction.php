<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateApplicationTemplateAction
{
    public static function execute(int $id)
    {
        $repo = new ApplicationTemplateRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error("not found");
        }
        $request = new Request();
        $body = $request->body;
        foreach ($body as $k => $v) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $k)));
            if (method_exists($entity, $setter)) {
                $entity->{$setter}($v);
            }
        }
        $repo->update($entity);
    }
}
