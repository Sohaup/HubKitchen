<?php

namespace PostApi\modules\HR\domain\services\applications;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationRepository;
use PostApi\shared\helpers\fecade\Files;

class DeleteApplicationAction
{
    public static function execute(int $id)
    {
        $applicationRepository = new ApplicationRepository();
        $application = $applicationRepository->findOne($id);
        if ($application) {
            $cvPath = $application->getCv();
            if ($cvPath) {
                if (Files::deleteFile($cvPath)) {
                    $applicationRepository->delete($id);
                } else {
                   throw new Error("Failed to delete CV file.");
                }
            }
        }
    }
}
