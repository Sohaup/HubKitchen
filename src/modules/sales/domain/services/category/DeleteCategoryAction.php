<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;

class DeleteCategoryAction
{
    public static function execute(int $id)
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->findOne($id);
        if ($category) {
            Retery::execute(function () use ($category) {
                Files::deleteFile($category->getImage());
            });
            $categoryRepository->delete($id);
        }
    }
}
