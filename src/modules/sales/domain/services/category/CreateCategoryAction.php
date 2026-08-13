<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\modules\sales\domain\entities\Category;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateCategoryAction
{
    public static function execute(Category $category)
    {
        $categoryRepository = new CategoryRepository();
        $imagePath = Retery::execute(function () {
            return Files::storeFile('image');
        });
        $category->setImage($imagePath);
        $categoryRepository->create($category);
        return SerializeToSerin::serialize($category);
      
    }
}