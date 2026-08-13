<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetCategoryItemAction
{
    public static function execute(int $id)
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->findOne($id);
        return SerializeToSerin::serialize($category);
    }
}