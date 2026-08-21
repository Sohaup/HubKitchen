<?php
namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\app\DB\repositories\JobDescriptionRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobDescriptionCollectionAction {
    public static function execute(array $items = null) {
        $jdRepository = new JobDescriptionRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $jdRepository->findAll());
        return $serin;
    }
}