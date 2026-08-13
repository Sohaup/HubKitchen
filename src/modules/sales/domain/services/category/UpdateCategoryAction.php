<?php

namespace PostApi\modules\sales\domain\services\category;

use PostApi\modules\sales\app\DB\repositories\CategoryRepository;
use PostApi\modules\sales\domain\entities\Category;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;

class UpdateCategoryAction
{
    public static function execute(Category $category, array $params, bool $isFile)
    {
        $categoryRepository = new CategoryRepository();
        $queue = new TaskQueue();
        if (isset($params['name'])) {
            $category->setName($params['name']);
        }
        if ($isFile) {
            $queue->push(new ClousreCommand(function () use ($category) {
                Retery::execute(function () use ($category) {
                    Files::deleteFile($category->getImage());
                });
            }));

            $queue->push(new ClousreCommand(function () {
                return Retery::execute(function () {
                    return Files::storeFile('image');
                });
            }));

            $results = $queue->execute();
            $category->setImage($results[1]);
        }

        $categoryRepository->update($category);
    }
}
