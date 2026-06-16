<?php

namespace PostApi\modules\HR\domain\services\applicationTemplate;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationTemplateRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationTemplateItemAction
{
    public static function execute(int $id)
    {
        $repo = new ApplicationTemplateRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error("not found");
        }
        return SerializeToSerin::serialize($entity);
    }
}
