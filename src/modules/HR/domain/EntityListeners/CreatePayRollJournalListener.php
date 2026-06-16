<?php

namespace PostApi\modules\HR\domain\EntityListeners;

use Override;
use PDO;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\PayrollRepository;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\modules\HR\domain\entities\Salery;
use PostApi\modules\HR\domain\services\payroll\CreatePayrollAction;
use PostApi\modules\HR\helpers\types\CalcType;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\queryBuilder\Interepter\Columns\QueryColumns;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\BasicCondition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\Condition;
use PostApi\shared\helpers\queryBuilder\Interepter\Conditions\Condition\ConditionOperators;
use PostApi\shared\helpers\queryBuilder\Interepter\Queries\DQL\Select;
use PostApi\shared\helpers\queryBuilder\Interepter\Table\QueryTable;
use PostApi\shared\templates\DB_Trait;
use SplObserver;
use SplSubject;

class CreatePayRollJournalListener implements SplObserver
{
    use DB_Trait;
    public function __construct()
    {
        $this->initialize();
    }
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreatePayrollAction && $subject->getCreatePayrollEvent() === "created") {
            $payRollRepo = new PayrollRepository();
            $employeeRepo = new EmployeeRepository();
            $selaryComponentRepo = new SaleryComponentRepository();
            $selaryRepo = new SaleryRepository();
            $payRoll = $subject->getPayroll();
            $request = new Request();
            $body = $request->body;
            $employee = $employeeRepo->findOne($body['employee_id']);
            $selaryComponent = $selaryComponentRepo->findOne($body['salery_component_id']);
            $payRollQueryTable = new QueryTable("HR.payroll_journal");
            $payRollQueryColumn = new QueryColumns(["*"]);
            $employeeIdCondition = new Condition("employee_id", ConditionOperators::EQUAL, $employee->getId());
            $payRollQueryCondition = new BasicCondition($employeeIdCondition);
            $payRollQuery = new Select(table: $payRollQueryTable->getQuery(), columns: $payRollQueryColumn->getColumns(), condition: $payRollQueryCondition->getCondition());
           
            $payRolls = $this->queryBuilder->select($payRollQuery->getQuery(), $payRollQueryCondition->getValues(), PDO::FETCH_ASSOC);
            $finalSelary = 0;
            if (!empty($payRolls)) {
                foreach ($payRolls as $payRoll) {
                    $payRollComp = $payRollRepo->findOne($payRoll['id']);
                    $payRollSelaryComp = $payRollComp->getSaleryComponent();
                    if ($payRollSelaryComp->getCalcType() == CalcType::INCREASE->value) {
                        $finalSelary += $payRollComp->getAmount();
                    } else {
                        $finalSelary -= $payRollComp->getAmount();
                    }
                }
            } else {
                if ($selaryComponent->getCalcType() == CalcType::INCREASE->value) {
                    $finalSelary = $payRoll->getAmount();
                } else {
                    $finalSelary = -$payRoll->getAmount();
                }
            }
            $employeeQueryTable = new QueryTable("HR.selaries");
            $employeeQueryColumn = new QueryColumns(["*"]);
            $employeeQueryCondition = new BasicCondition($employeeIdCondition);
            $employeeQuery = new Select(table: $employeeQueryTable->getQuery(), columns: $employeeQueryColumn->getColumns(), condition: $employeeQueryCondition->getCondition());
            $selaryEmployees = $this->queryBuilder->select($employeeQuery->getQuery(), $employeeQueryCondition->getValues(), PDO::FETCH_ASSOC);
            if (!empty($selaryEmployees)) {
                foreach ($selaryEmployees as $selaryEmployee) {
                    $selary = $selaryRepo->findOne($selaryEmployee['id']);
                    $selary->setSalery($finalSelary);
                    $selaryRepo->update($selary);
                }
            } else {
                $selary = new Salery(id: null, employee: $employee, salery: $finalSelary);
                $selaryRepo->create($selary);
            }
        }
    }
}
