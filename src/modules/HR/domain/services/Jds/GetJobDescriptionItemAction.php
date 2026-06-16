<?php
namespace PostApi\modules\HR\domain\services\Jds;

use PostApi\modules\HR\domain\entities\JobDescription;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetJobDescriptionItemAction {
    public static function execute(JobDescription $jd) {
        $serin = SerializeToSerin::serialize($jd);
        return $serin;
    }
}