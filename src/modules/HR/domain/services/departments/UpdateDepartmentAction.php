<?php
namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;
use PostApi\modules\HR\domain\entities\Department;

class UpdateDepartmentAction {
    public static function execute(Department $department) {
        $departmentsRepository = new DepartmentRepository();
        $departmentsRepository->update($department);        
    }   
}