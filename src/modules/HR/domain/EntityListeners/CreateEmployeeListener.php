<?php
namespace PostApi\modules\HR\domain\EntityListeners;

use Override;
use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\modules\HR\domain\entities\TurnCycle;
use PostApi\modules\HR\domain\services\employee\CreateEmployeeAction;
use SplObserver;
use SplSubject;

class CreateEmployeeListener implements SplObserver {
    #[Override]
    public function update(SplSubject $subject): void
    {
       if ($subject instanceof CreateEmployeeAction && $subject->getEvent() == "created") {
            $turnCycleRepo = new TurnCycleRepository(); 
            $employee = $subject->getEmployee();
            $start_at = date('r');            
            $turnCycle = new TurnCycle(null , $start_at , null , $employee);
            $turnCycleRepo->create($turnCycle);
       }
    }
}