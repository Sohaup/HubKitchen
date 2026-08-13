<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCategoryCollectionAction
{
    public static function execute()
    {
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->findAll();
        return SerializeToSerin::serializeCollection($categories);
    }
}