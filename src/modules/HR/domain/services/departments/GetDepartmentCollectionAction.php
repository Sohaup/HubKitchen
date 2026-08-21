<?php
namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetDepartmentCollectionAction {
    public static function execute(array $items = null) {
        $departmentsRepository = new DepartmentRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $departmentsRepository->findAll());
        return $serin;
    }
}