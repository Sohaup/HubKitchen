<?php
namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobDescriptionCollectionAction {
    public static function execute() {
        $jdRepository = new JobDescriptionRepository();
        $jds = $jdRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($jds);
        return $serin;
    }
}