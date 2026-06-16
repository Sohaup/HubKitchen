<?php
namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;

class DeleteJobDescriptionAction {
    public static function execute(int $id) {
        $jdRepository = new JobDescriptionRepository();
        $jd = $jdRepository->findOne($id);
        if ($jd) {
            $jdRepository->delete($id);
        }
    } 
}