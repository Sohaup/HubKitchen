<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCategoryCollectionAction
{
    public static function execute(array $items = null)
    {
        $categoryRepository = new CategoryRepository();
        return SerializeToSerin::serializeCollection($items ?? $categoryRepository->findAll());
    }
}
