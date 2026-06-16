<?php
namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\domain\entities\Department;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateDepartmentAction {
    public static function execute(Department $department) {        
        $departmentsRepository = new DepartmentRepository();
        $departmentsRepository->create($department);
        $serin = SerializeToSerin::serialize($department);
        return $serin;
    }
}