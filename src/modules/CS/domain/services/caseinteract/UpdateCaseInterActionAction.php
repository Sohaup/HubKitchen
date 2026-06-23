<?php

namespace PostApi\modules\CS\domain\services\caseinteract;

use PostApi\modules\CS\app\DB\repositories\CaseInterActionRepository;
use PostApi\modules\CS\domain\entities\CaseInterAction;
use PostApi\modules\CS\app\DB\repositories\CustomerRepository;
use PostApi\modules\CS\app\DB\repositories\EmployeeRepository as CsEmployeeRepo;
use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\modules\CS\app\DB\repositories\TicketRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateCaseInterActionAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new CaseInterActionRepository();
        $customerRepo = new CustomerRepository();
        $employeeRepo = new CsEmployeeRepo();
        $actionRepo = new ActionRepository();
        $statusRepo = new StatusRepository();
        $ticketRepo = new TicketRepository();

        $entity = new CaseInterAction();
        $entity->setId($id);
        $customer = $customerRepo->findOne($params['customer_id']);
        $entity->setCustomer($customer);
        $employee = $employeeRepo->findOne($params['employee_id']);
        $entity->setEmployee($employee);
        $action = $actionRepo->findOne($params['action_id']);
        $entity->setAction($action);
        $status = $statusRepo->findOne($params['status_id']);
        $entity->setStatus($status);
        $entity->setTakedAction($params['action']);      
        $ticket = $ticketRepo->findOne($params['ticket_id']);
        $entity->setTicket($ticket);
        
        $repo->update($entity);
    }
}
