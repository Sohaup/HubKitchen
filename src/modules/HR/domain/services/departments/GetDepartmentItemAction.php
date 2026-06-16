<?php
namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetDepartmentItemAction {
    public static function execute(int $id) {
        $departmentsRepository = new DepartmentRepository();
        $department = $departmentsRepository->findOne($id);
        if ($department) {
            $serin = SerializeToSerin::serialize($department);
            return $serin;
        }
    }
}