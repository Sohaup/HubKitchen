<?php

namespace PostApi\modules\CS\app\DB\models;

use PDO;
use PDOException;
use PostApi\modules\CS\domain\entities\CaseInterAction;

class CaseInterActionMapper
{
    private array $identityMap = [];
    private CustomerMapper $customerMapper;
    private EmployeeMapper $employeeMapper;
    private ActionMapper $actionMapper;
    private StatusMapper $statusMapper;
    private TicketMapper $ticketMapper;

    public function __construct(private PDO $db)
    {
        $this->customerMapper = new CustomerMapper($db);
        $this->employeeMapper = new EmployeeMapper($db);
        $this->actionMapper = new ActionMapper($db);
        $this->statusMapper = new StatusMapper($db);
        $this->ticketMapper = new TicketMapper($db);
    }

    public function findOne(string $id)
    {
        if (isset($this->identityMap[$id])) {
            return $this->identityMap[$id];
        }
        $stmt = $this->db->prepare("SELECT * FROM cs.case_interactions WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $entity = new CaseInterAction();
            $entity->setId($row['id']);
            $customer = $this->customerMapper->findOne($row['customer_id']);
            $entity->setCustomer($customer);
            $employee = $this->employeeMapper->findOne($row['employee_id']);
            $entity->setEmployee($employee);
            $action = $this->actionMapper->findOne($row['action_id']);
            $entity->setAction($action);
            $status = $this->statusMapper->findOne($row['status_id']);
            $entity->setStatus($status);
            $ticket = $this->ticketMapper->findOne($row['ticket_id']);
            $entity->setTicket($ticket);
            $entity->setTakedAction($row['action']);
            $entity->setInteractedAt(new \DateTime($row['interacted_at']));
            $this->identityMap[$id] = $entity;
            return $entity;
        }
    }

    public function findAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM cs.case_interactions");
        $stmt->execute([]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (!isset($this->identityMap[$row['id']])) {
                $entity = new CaseInterAction();
                $entity->setId($row['id']);
                $customer = $this->customerMapper->findOne($row['customer_id']);
                $entity->setCustomer($customer);
                $employee = $this->employeeMapper->findOne($row['employee_id']);
                $entity->setEmployee($employee);
                $action = $this->actionMapper->findOne($row['action_id']);
                $entity->setAction($action);
                $status = $this->statusMapper->findOne($row['status_id']);
                $entity->setStatus($status);
                $ticket = $this->ticketMapper->findOne($row['ticket_id']);
                $entity->setTicket($ticket);
                $entity->setTakedAction($row['action']);
                $entity->setInteractedAt(new \DateTime($row['interacted_at']));
                $this->identityMap[$row['id']] = $entity;
            }
        }
        return $this->identityMap;
    }

    public function insert(CaseInterAction $entity)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO cs.case_interactions(customer_id, employee_id, action_id, status_id, action , ticket_id) VALUES(?, ?, ?, ?, ? , ?) RETURNING id");
            
            $stmt->execute([
                $entity->getCustomer()->getId(),
                $entity->getEmployee()->getId(),
                $entity->getAction()->getId(),
                $entity->getStatus()->getId(),
                $entity->getTakedAction() ,
                $entity->getTicket()->getId()  
            ]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

            $entity->setId($id);
            $this->identityMap[$id] = $entity;
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public function update(CaseInterAction $entity)
    {
        $stmt = $this->db->prepare("UPDATE cs.case_interactions SET customer_id = ?, employee_id = ?, action_id = ?, status_id = ?, action = ? , ticket_id = ? WHERE id = ?");
        $stmt->execute([
            $entity->getCustomer()->getId(),
            $entity->getEmployee()->getId(),
            $entity->getAction()->getId(),
            $entity->getStatus()->getId(),
            $entity->getTakedAction(),
            $entity->getTicket()->getId() ,
            $entity->getId()
        ]);
        $this->identityMap[$entity->getId()] = $entity;
    }

    public function delete(string $id)
    {
        $stmt = $this->db->prepare("DELETE FROM cs.case_interactions WHERE id = ?");
        $stmt->execute([$id]);
        unset($this->identityMap[$id]);
    }
}
