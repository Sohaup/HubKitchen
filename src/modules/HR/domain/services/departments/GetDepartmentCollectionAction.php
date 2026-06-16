<?php
namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetDepartmentCollectionAction {
    public static function execute() {
        $departmentsRepository = new DepartmentRepository();
        $departments = $departmentsRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($departments);
        return $serin;
    }
}